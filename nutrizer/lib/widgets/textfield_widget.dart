import 'package:flutter/material.dart';

class TextFormWidget extends StatelessWidget {
  final String labelText;
  final String Function(String) validator;
  final String Function(String) onSaved;
  final IconData icon;
  final TextInputType keyboardType;
  final String initialValue;

  TextFormWidget(
      {@required this.labelText,
      this.validator,
      this.icon,
      this.keyboardType,
      this.onSaved,
      this.initialValue});
  @override
  Widget build(BuildContext context) {
    return TextFormField(
      initialValue: initialValue,
      keyboardType: keyboardType,
      decoration: InputDecoration(
        labelText: labelText,
        prefixIcon: icon != null
            ? Icon(
                icon,
                size: 20,
                color: Theme.of(context).primaryColor,
              )
            : null,
      ),
      validator: validator,
      onSaved: onSaved,
      style: TextStyle(fontSize: 20),
    );
  }
}

class TextPasswordFormWidget extends StatefulWidget {
  final String initialValue;
  final String labelText;
  final String Function(String) validator;
  final String Function(String) onSaved;
  final IconData icon;
  final TextEditingController controller;

  TextPasswordFormWidget(
      {@required this.labelText,
      this.validator,
      this.initialValue,
      this.icon,
      this.onSaved,
      this.controller,
      Key key})
      : super(key: key);

  @override
  _TextPasswordFormWidgetState createState() => _TextPasswordFormWidgetState();
}

class _TextPasswordFormWidgetState extends State<TextPasswordFormWidget> {
  bool obscureText = true;
  @override
  Widget build(BuildContext context) {
    return TextFormField(
      initialValue: widget.initialValue,
      obscureText: obscureText,
      controller: widget.controller,
      decoration: InputDecoration(
        labelText: widget.labelText,
        prefixIcon: widget.icon != null
            ? Icon(
                widget.icon,
                size: 20,
                color: Theme.of(context).primaryColor,
              )
            : null,
        suffixIcon: IconButton(
          onPressed: () {
            setState(() {
              obscureText = !obscureText;
            });
          },
          icon: Icon(obscureText ? Icons.visibility : Icons.visibility_off),
        ),
      ),
      validator: widget.validator,
      onSaved: widget.onSaved,
      style: TextStyle(fontSize: 16),
    );
  }
}

class TextDateFormWidget extends StatefulWidget {
  final String labelText;
  final String Function(String) validator;
  final String Function(String) onSaved;

  final IconData icon;

  TextDateFormWidget(
      {@required this.labelText, this.validator, this.icon, this.onSaved});

  @override
  _TextDateFormWidgetState createState() => _TextDateFormWidgetState();
}

class _TextDateFormWidgetState extends State<TextDateFormWidget> {
  TextEditingController _dateCtl = TextEditingController();
  // FocusNode _focusNode = FocusNode();
  Future _selectDate() async {
    DateTime picked = await showDatePicker(
        context: context,
        initialDate: new DateTime.now(),
        firstDate: new DateTime(1900),
        lastDate: new DateTime.now());
    if (picked != null)
      setState(() => _dateCtl.value =
          TextEditingValue(text: picked.toLocal().toString().split(' ')[0]));
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: _selectDate,
      child: AbsorbPointer(
        child: TextFormField(
          controller: _dateCtl,
          // focusNode: _focusNode,
          decoration: InputDecoration(
            labelText: widget.labelText,
            prefixIcon: widget.icon != null
                ? Icon(
                    widget.icon,
                    size: 20,
                    color: Theme.of(context).primaryColor,
                  )
                : null,
          ),
          validator: widget.validator,
          onSaved: widget.onSaved,
          style: TextStyle(fontSize: 16),
        ),
      ),
    );
  }
}

class TextFormV2Widget extends StatelessWidget {
  final String hintText;
  final String labelText;
  final bool enabled;
  final String Function(String) validator;
  final String Function(String) onSaved;
  final TextInputType keyboardType;
  final String initialValue;

  TextFormV2Widget(
      {this.hintText,
      this.validator,
      this.enabled=true,
      this.labelText,
      this.keyboardType,
      this.onSaved,
      this.initialValue});
  @override
  Widget build(BuildContext context) {
    return TextFormField(
      initialValue: initialValue,
      keyboardType: keyboardType,
      enabled: enabled,
      decoration: InputDecoration(
          contentPadding: EdgeInsets.symmetric(horizontal: 20, vertical: 15),
          hintText: hintText,
          floatingLabelBehavior: FloatingLabelBehavior.always,
          labelText: labelText,
          labelStyle: TextStyle(fontWeight: FontWeight.bold, fontSize: 20)),
      validator: validator,
      onSaved: onSaved,
      style: TextStyle(
          fontSize: 16,
          color: Theme.of(context).accentColor,
          fontWeight: FontWeight.w700),
    );
  }
}

class TextPasswordFormV2Widget extends StatefulWidget {
  final String initialValue;
  final String labelText;
  final String hintText;
  final String Function(String) validator;
  final String Function(String) onSaved;
  final TextEditingController controller;

  TextPasswordFormV2Widget(
      {@required this.labelText,
      this.validator,
      this.initialValue,
      this.onSaved,
      this.hintText,
      this.controller,
      Key key})
      : super(key: key);

  @override
  _TextPasswordFormV2WidgetState createState() =>
      _TextPasswordFormV2WidgetState();
}

class _TextPasswordFormV2WidgetState extends State<TextPasswordFormV2Widget> {
  bool obscureText = true;
  @override
  Widget build(BuildContext context) {
    return TextFormField(
      initialValue: widget.initialValue,
      obscureText: obscureText,
      controller: widget.controller,
      decoration: InputDecoration(
        labelText: widget.labelText,
        contentPadding: EdgeInsets.symmetric(horizontal: 20, vertical: 15),
        hintText: widget.hintText,
        hintStyle: TextStyle(fontSize: 14),
        floatingLabelBehavior: FloatingLabelBehavior.always,
        labelStyle: TextStyle(fontWeight: FontWeight.bold, fontSize: 20),
        suffixIcon: IconButton(
          onPressed: () {
            setState(() {
              obscureText = !obscureText;
            });
          },
          icon: Icon(obscureText ? Icons.visibility : Icons.visibility_off),
        ),
      ),
      validator: widget.validator,
      onSaved: widget.onSaved,
      style: TextStyle(
          fontSize: 16,
          color: Theme.of(context).accentColor,
          fontWeight: FontWeight.w700),
    );
  }
}
