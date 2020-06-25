part of 'profile_bloc.dart';

abstract class ProfileEvent extends Equatable {
  const ProfileEvent();
}


class ProfileUpdateBMI extends ProfileEvent {
  final double weight;
  final double height;

  const ProfileUpdateBMI({
    this.weight,this.height
  });

  @override
  List<Object> get props => [weight,height];
}

class ProfileUpdateUser extends ProfileEvent {
  final UserModel userModel;

  const ProfileUpdateUser({
    this.userModel
  });

  @override
  List<Object> get props => [userModel];
}


class ProfileChangedBMIWeight extends ProfileEvent {
  final double weight;

  const ProfileChangedBMIWeight({
   @required this.weight
  });

  @override
  List<Object> get props => [weight];
}

class ProfileChangedBMIHeight extends ProfileEvent {
  final double height;

  const ProfileChangedBMIHeight({
    @required this.height
  });

  @override
  List<Object> get props => [height];
}