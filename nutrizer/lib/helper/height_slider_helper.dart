import 'dart:math' as math;

import 'package:flutter/material.dart';
import 'package:nutrizer/helper/assets_helper.dart';

class HeightSliderHelper extends StatefulWidget {
  final int maxHeight;
  final int minHeight;
  final int height;
  final int stepHeight;
  final String personImagePath;
  final Color primaryColor;
  final Color accentColor;
  final Color numberLineColor;
  final Color currentHeightTextColor;
  final Color sliderCircleColor;
  final ValueChanged<int> onChange;

  const HeightSliderHelper(
      {Key key,
      @required this.height,
      @required this.onChange,
      this.maxHeight = 190,
      this.minHeight = 145,
      this.stepHeight=5,
      this.primaryColor,
      this.accentColor,
      this.numberLineColor,
      this.currentHeightTextColor,
      this.sliderCircleColor,
      this.personImagePath})
      : super(key: key);

  int get totalUnits => maxHeight - minHeight;

  @override
  _HeightSliderHelperState createState() => _HeightSliderHelperState();
}

class _HeightSliderHelperState extends State<HeightSliderHelper> {
  double startDragYOffset;
  int startDragHeight;
  double widgetHeight = 50;
  double labelFontSize = 16.0;

  double get _pixelsPerUnit {
    return _drawingHeight / widget.totalUnits;
  }

  double get _sliderPosition {
    double halfOfBottomLabel = labelFontSize / 2;
    int unitsFromBottom = widget.height - widget.minHeight;
    return halfOfBottomLabel + unitsFromBottom * _pixelsPerUnit;
  }

  double get _drawingHeight {
    double totalHeight = this.widgetHeight;
    double marginBottom = 12.0;
    double marginTop = 12.0;
    return totalHeight - (marginBottom + marginTop + labelFontSize);
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.all(12),
      child: LayoutBuilder(builder: (context, constraints) {
        this.widgetHeight = constraints.maxHeight;
        return GestureDetector(
          behavior: HitTestBehavior.translucent,
          onTapDown: this._onTapDown,
          onVerticalDragStart: this._onDragStart,
          onVerticalDragUpdate: this._onDragUpdate,
          child: Stack(
            overflow: Overflow.visible,
            children: <Widget>[
              _drawPersonImage(),
              _drawSlider(),
              _drawLabels(),
            ],
          ),
        );
      }),
    );
  }

  _onTapDown(TapDownDetails tapDownDetails) {
    int height = _globalOffsetToHeight(tapDownDetails.globalPosition);
    widget.onChange(_normalizeHeight(height));
  }

  int _normalizeHeight(int height) {
    return math.max(widget.minHeight, math.min(widget.maxHeight, height));
  }

  int _globalOffsetToHeight(Offset globalOffset) {
    RenderBox getBox = context.findRenderObject();
    Offset localPosition = getBox.globalToLocal(globalOffset);
    double dy = localPosition.dy;
    dy = dy - 12.0 - labelFontSize / 2;
    int height = widget.maxHeight - (dy ~/ _pixelsPerUnit);
    return height;
  }

  _onDragStart(DragStartDetails dragStartDetails) {
    int newHeight = _globalOffsetToHeight(dragStartDetails.globalPosition);
    widget.onChange(newHeight);
    setState(() {
      startDragYOffset = dragStartDetails.globalPosition.dy;
      startDragHeight = newHeight;
    });
  }

  _onDragUpdate(DragUpdateDetails dragUpdateDetails) {
    double currentYOffset = dragUpdateDetails.globalPosition.dy;
    double verticalDifference = startDragYOffset - currentYOffset;
    int diffHeight = verticalDifference ~/ _pixelsPerUnit;
    int height = _normalizeHeight(startDragHeight + diffHeight);
    setState(() => widget.onChange(height));
  }

  Widget _drawSlider() {
    return Positioned(
      child: _HeightSliderInteral(
          height: widget.height,
          primaryColor: widget.primaryColor ?? Theme.of(context).primaryColor,
          accentColor: widget.accentColor ?? Theme.of(context).accentColor,
          currentHeightTextColor:
              widget.currentHeightTextColor ?? Theme.of(context).accentColor,
          sliderCircleColor:
              widget.sliderCircleColor ?? Theme.of(context).primaryColor),
      left: 0.0,
      right: 0.0,
      bottom: _sliderPosition,
    );
  }

  Widget _drawLabels() {
    int labelsToDisplay = widget.totalUnits ~/ widget.stepHeight + 1;
    List<Widget> labels = List.generate(
      labelsToDisplay,
      (idx) {
        return Row(
          mainAxisAlignment: MainAxisAlignment.end,
          children: <Widget>[
            Text(
              "${widget.maxHeight - widget.stepHeight * idx}",
              style: TextStyle(
                color: widget.numberLineColor ?? Theme.of(context).accentColor,
                fontSize: labelFontSize,
              ),
            ),
            Container(
              margin: EdgeInsets.only(left: 12.0),
              height: 3,
              width: 10,
              color: Theme.of(context).primaryColor,
            )
          ],
        );
      },
    );

    return Align(
      alignment: Alignment.centerRight,
      child: IgnorePointer(
        child: Container(
          padding: EdgeInsets.only(
            bottom: 12.0,
            top: 12.0,
          ),
            
          child: Column(
            children: labels,
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
          ),
          decoration: BoxDecoration(
              border: Border(
                  right: BorderSide(
                    width: 3,
                    color: Theme.of(context).primaryColor))),
        ),
      ),
    );
  }

  Widget _drawPersonImage() {
    double personImageHeight = _sliderPosition + 12.0;
    if (widget.personImagePath == null) {
      return Align(
        alignment: Alignment.bottomCenter,
        child: Image.asset(
          AssetsHelper.person,
          height: personImageHeight,
          width: personImageHeight / 3,
        ),
      );
    }

    return Align(
      alignment: Alignment.bottomCenter,
      child: Image.asset(
        widget.personImagePath,
        height: personImageHeight,
        width: personImageHeight / 3,
      ),
    );
  }
}

class _HeightSliderInteral extends StatelessWidget {
  final int height;
  final Color primaryColor;
  final Color accentColor;
  final Color currentHeightTextColor;
  final Color sliderCircleColor;

  const _HeightSliderInteral(
      {Key key,
      @required this.height,
      @required this.primaryColor,
      @required this.accentColor,
      @required this.currentHeightTextColor,
      @required this.sliderCircleColor})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return IgnorePointer(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          _SliderLabel(
              height: this.height,
              currentHeightTextColor: this.currentHeightTextColor),
          Row(
            children: <Widget>[
              _SliderCircle(sliderCircleColor: this.sliderCircleColor),
              Expanded(child: _SliderLine(primaryColor: this.primaryColor)),
            ],
          ),
        ],
      ),
    );
  }
}

class _SliderLabel extends StatelessWidget {
  final int height;
  final Color currentHeightTextColor;

  const _SliderLabel(
      {Key key, @required this.height, @required this.currentHeightTextColor})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        left: 4.0,
        bottom: 2.0,
      ),
      child: Text(
        "$height",
        style: TextStyle(
          fontSize: 18.0,
          color: this.currentHeightTextColor,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }
}

class _SliderLine extends StatelessWidget {
  final Color primaryColor;

  const _SliderLine({Key key, @required this.primaryColor}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
      mainAxisSize: MainAxisSize.max,
      children: List.generate(
          40,
          (i) => Expanded(
                child: Container(
                  height: 2.0,
                  decoration: BoxDecoration(
                      color: i.isEven ? this.primaryColor : Colors.white),
                ),
              )),
    );
  }
}

class _SliderCircle extends StatelessWidget {
  final Color sliderCircleColor;

  const _SliderCircle({Key key, @required this.sliderCircleColor})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      width: 32.0,
      height: 32.0,
      decoration: BoxDecoration(
        color: this.sliderCircleColor,
        shape: BoxShape.circle,
      ),
      child: Icon(
        Icons.unfold_more,
        color: Colors.white,
        size: 0.6 * 32.0,
      ),
    );
  }
}
