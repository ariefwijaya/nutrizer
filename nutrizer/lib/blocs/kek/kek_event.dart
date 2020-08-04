part of 'kek_bloc.dart';

abstract class KekEvent extends Equatable {
  const KekEvent();
@override
  List<Object> get props => [];
}

class KekFetchList extends KekEvent {
  final int offset;
  KekFetchList({this.offset=0});
  @override
  List<Object> get props => [offset];
}

class KekFetchDetail extends KekEvent {
  final String id;
  KekFetchDetail({this.id});
  @override
  List<Object> get props => [id];
}

