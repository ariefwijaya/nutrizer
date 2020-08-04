part of 'profile_bloc.dart';

abstract class ProfileEvent extends Equatable {
  const ProfileEvent();

  List<Object> get props => [];
}

class ProfileUpdateBMI extends ProfileEvent {
  final double weight;
  final double height;

  const ProfileUpdateBMI({this.weight, this.height});

  @override
  List<Object> get props => [weight, height];
}

class ProfileUpdateUser extends ProfileEvent {
  final FormEditProfileModel formData;

  const ProfileUpdateUser({this.formData});

  @override
  List<Object> get props => [formData];
}

class ProfileChangePassword extends ProfileEvent {
  final FormChangePasswordModel formData;

  const ProfileChangePassword({this.formData});

  @override
  List<Object> get props => [formData];
}

class ProfileChangedBMIWeight extends ProfileEvent {
  final double weight;

  const ProfileChangedBMIWeight({@required this.weight});

  @override
  List<Object> get props => [weight];
}

class ProfileChangedBMIHeight extends ProfileEvent {
  final double height;

  const ProfileChangedBMIHeight({@required this.height});

  @override
  List<Object> get props => [height];
}

class ProfileUserFetch extends ProfileEvent {}
