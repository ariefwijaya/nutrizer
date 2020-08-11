import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/domain/user_domain.dart';

typedef void OnUploadProgressCallback(int sentBytes, int totalBytes);

class NetworkHelper {
  // next three lines makes this class a Singleton
  static NetworkHelper _instance = new NetworkHelper.internal();

  NetworkHelper.internal();

  factory NetworkHelper() => _instance;

  Future<Map<String, String>> getHeaderAuth() async {
    UserDomain _userDomain = UserDomain();
    final user = await _userDomain.getCurrentSession();
    var authHeaders = {
      'X-DEVICE-ID': "1234",
      'X-API-KEY': apiKey,
      'Authorization': user != null ? user.token : ""
    };
    return authHeaders;
    // application/x-www-form-urlencoded
  }

  Future<Map<String, dynamic>> get(String path,
      {String url = "$baseHost", Map<String, dynamic> headers, body}) async {
    final newUrl = Uri.http(url, baseUrl + path, body);
    Map<String, String> authHeaders = await getHeaderAuth();
    if (headers != null) authHeaders.addAll(headers);
//    print(newUrl);

    return await http
        .get(newUrl, headers: authHeaders)
        .then((http.Response response) {
      return handleResponse(response);
    }).timeout(Duration(seconds: 9), onTimeout: () {
      throw ("Connection Timeout. Please check your network");
    });
  }

  Future<Map<String, dynamic>> post(String path,
      {String url = "$baseHttp$baseHost$baseUrl",
      Map<String, String> headers,
      body,
      encoding}) async {
    Map<String, String> authHeaders = await getHeaderAuth();
    if (headers != null) authHeaders.addAll(headers);
    //  print(authHeaders);
    // print(url + path);
    // print(body);
    return await http
        .post(url + path, body: body, headers: authHeaders, encoding: encoding)
        .then((http.Response response) {
      return handleResponse(response);
    }).timeout(Duration(seconds: 15), onTimeout: () {
      throw ("Connection Timeout. Please check your network");
    });
  }

  Future<Map<String, dynamic>> delete(String path,
      {String url = "$baseHost", Map<String, dynamic> headers, body}) async {
    final newUrl = Uri.http(url, baseUrl + path, body);
    Map<String, String> authHeaders = await getHeaderAuth();
    if (headers != null) authHeaders.addAll(headers);
    return await http
        .delete(
      newUrl,
      headers: authHeaders,
    )
        .then((http.Response response) {
      return handleResponse(response);
    }).timeout(Duration(seconds: 9), onTimeout: () {
      throw ("Connection Timeout. Please check your network");
    });
  }

  Future<Map<String, dynamic>> put(String path,
      {String url = "$baseHttp$baseHost$baseUrl",
      Map<String, String> headers,
      body,
      encoding}) async {
    Map<String, String> authHeaders = await getHeaderAuth();
    if (headers != null) authHeaders.addAll(headers);
    return await http
        .put(url + path, body: body, headers: authHeaders, encoding: encoding)
        .then((http.Response response) {
      return handleResponse(response);
    }).timeout(Duration(seconds: 9), onTimeout: () {
      throw ("Connection Timeout. Please check your network");
    });
  }

  static bool trustSelfSigned = true;

  HttpClient getHttpClient() {
    HttpClient httpClient = new HttpClient()
      ..connectionTimeout = const Duration(seconds: 10)
      ..badCertificateCallback =
          ((X509Certificate cert, String host, int port) => trustSelfSigned);

    return httpClient;
  }

  Future<Map<String, dynamic>> uploadFiles(String path,
      {String url = "$baseHttp$baseHost$baseUrl",
      Map<String, String> headers,
      Map<String, dynamic> body,
      List<MultiFileCustom> files,
      OnUploadProgressCallback onUploadProgress,
      Function onUploadDone,
      Function(String) onUploadError}) async {
    Map<String, String> authHeaders = await getHeaderAuth();
    if (headers != null) authHeaders.addAll(headers);

    var uri = Uri.parse(url + path);
    final httpClient = getHttpClient();

    final requestPost = await httpClient.postUrl(uri);

    // var request = new http.MultipartRequest("POST", uri);
    var request = new http.MultipartRequest("", Uri.parse("uri"));
    request.headers.addAll(Map<String, String>.from(authHeaders));
    request.fields.addAll(Map<String, String>.from(body));

    for (int i = 0; i < files.length; i++) {
      var multipartFile = await http.MultipartFile.fromPath(
          files[i].fieldName, files[i].files.path,
          contentType: MediaType.parse(files[i].contentType),
          filename: files[i].files.path.split('/').last);
      request.files.add(multipartFile);
    }
    var streamedResponse = request.finalize();

    int byteCount = 0;
    final totalByteLength = request.contentLength;

    for (var key in request.headers.keys) {
      requestPost.headers.add(key, request.headers[key]);
    }

    requestPost.contentLength = totalByteLength;

    Stream<List<int>> streamUpload = streamedResponse.transform(
      new StreamTransformer.fromHandlers(
        handleData: (data, sink) {
          sink.add(data);
          byteCount += data.length;

          if (onUploadProgress != null) {
            print("Call onUploadProgress ");
            onUploadProgress(byteCount, totalByteLength);
            // CALL STATUS CALLBACK;
          }
        },
        handleError: (error, stack, sink) {
          if (onUploadError != null) {
            onUploadError(error);
          }
          // print("Upload Error: $error");
          throw error;
        },
        handleDone: (sink) {
          sink.close();

          // print("Upload DOne");
          if (onUploadDone != null) {
            onUploadDone();
          }
          // UPLOAD DONE;
        },
      ),
    );

    await requestPost.addStream(streamUpload);

    final httpResponse = await requestPost.close();

    var statusCode = httpResponse.statusCode;

    if (statusCode ~/ 100 != 2) {
      throw Exception(
          'Error uploading file, Status code: ${httpResponse.statusCode}');
    } else {
      return await readResponseClient(httpResponse);
    }
  }

  Future<Map<String, dynamic>> readResponseClient(HttpClientResponse response) {
    var completer = new Completer<Map<String, dynamic>>();
    var contents = new StringBuffer();
    response.transform(utf8.decoder).listen((String data) {
      contents.write(data);
    }, onDone: () => completer.complete(json.decode(contents.toString())));
    return completer.future;
  }

  Map<String, dynamic> handleResponse(http.Response response) {
    final int statusCode = response.statusCode;
    // debugPrint(response.body.toString());
    if (statusCode == 401) {
      // debugPrint(response.statusCode.toString());
      // debugPrint(response.body.toString());
      throw ("Unauthorized");
    } else if (statusCode != 200) {
      // debugPrint(response.statusCode.toString());
      // debugPrint(response.body.toString());
      throw ("Error while fetching data");
    }
    try {
      return json.decode(response.body);
    } catch (e) {
      // debugPrint(response.body);
      throw e;
    }
  }
}

class MultiFileCustom {
  final String fieldName;
  final File files;
  final String contentType;

  MultiFileCustom({this.files, this.fieldName, this.contentType});
}
