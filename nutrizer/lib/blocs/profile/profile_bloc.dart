import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/user_model.dart';
import 'package:meta/meta.dart';

part 'profile_event.dart';
part 'profile_state.dart';

class ProfileBloc extends Bloc<ProfileEvent, ProfileState> {
  final UserDomain _userDomain = UserDomain();

  @override
  ProfileState get initialState => ProfileInitial();

  @override
  Stream<ProfileState> mapEventToState(
    ProfileEvent event,
  ) async* {
    if (event is ProfileChangedBMIWeight) {
      yield ProfileBMIWeightChanged(weight: event.weight);
    }

    if (event is ProfileChangedBMIHeight) {
      yield ProfileBMIHeightChanged(height: event.height);
    }

    if (event is ProfileUpdateBMI) {
      yield* _mapProfileUpdateBMIToState(event.height, event.weight);
    }

    if (event is ProfileUpdateUser) {
      yield* _mapProfileUpdateUserToState(event.userModel);
    }
  }

  Stream<ProfileState> _mapProfileUpdateBMIToState(
      double height, double weight) async* {
    try {
      yield ProfileLoading();
      if (!await _userDomain.updateUserProfileBMI(height, weight))
        throw "Failed to Update";
      UserModel userModel = await _userDomain.getCurrentSession();
      userModel.weight = weight;
      userModel.height = height;
      await _userDomain.updateSession(userModel);
      yield ProfileSuccess();
    } catch (error) {
      yield ProfileFailure(error: error.toString());
    }
  }

  Stream<ProfileState> _mapProfileUpdateUserToState(
      UserModel userModel) async* {
    try {
      yield ProfileLoading();
      await _userDomain.updateUserProfile(userModel);
      UserModel currentUser = await _userDomain.getCurrentSession();
      currentUser.nickname = userModel.nickname;
      currentUser.email = userModel.email;
      currentUser.birthday = userModel.birthday;
      await _userDomain.updateSession(currentUser);
      yield ProfileSuccess();
    } catch (error) {
      yield ProfileFailure(error: error.toString());
    }
  }
}
