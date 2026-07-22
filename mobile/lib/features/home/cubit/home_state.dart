import 'package:cms_mobile/features/home/data/models/page_model.dart';

sealed class HomeState {}

final class HomeInitial extends HomeState {}

final class HomeLoading extends HomeState {}

final class HomeLoaded extends HomeState {
  HomeLoaded(this.pages);
  final List<PageModel> pages;
}

final class HomeError extends HomeState {
  HomeError(this.message);
  final String message;
}
