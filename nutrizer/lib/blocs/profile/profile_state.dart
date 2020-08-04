part of 'profile_bloc.dart';

abstract class ProfileState extends Equatable {
  const ProfileState();
  @override
  List<Object> get props => [];
}

class ProfileInitial extends ProfileState {}

class ProfileLoading extends ProfileState {}

class ProfileSuccess extends ProfileState {}

class ProfileFailure extends ProfileState {
  final String error;

  const ProfileFailure({this.error});

  @override
  List<Object> get props => [error];
}

class ProfileBMIHeightChanged extends ProfileState {
  final double height;

  const ProfileBMIHeightChanged({
    this.height
  });

  @override
  List<Object> get props => [height];
}

class ProfileBMIWeightChanged extends ProfileState {
  final double weight;

  const ProfileBMIWeightChanged({
    this.weight
  });

  @override
  List<Object> get props => [weight];
}


class ProfileFetchLoading extends ProfileState {}

class ProfileFetchSuccess extends ProfileState {
  final UserModel userModel;

  ProfileFetchSuccess({this.userModel});

  String get firstName => userModel.nickname.split(" ")[0];

  @override
  List<Object> get props => [userModel];
}

class ProfileFetchFailure extends ProfileState {
  final String error;

  const ProfileFetchFailure({this.error});

  @override
  List<Object> get props => [error];
}
