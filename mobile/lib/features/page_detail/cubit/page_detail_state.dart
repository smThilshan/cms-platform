import 'package:cms_mobile/features/home/data/models/page_model.dart';

sealed class PageDetailState {}

final class PageDetailInitial extends PageDetailState {}

final class PageDetailLoading extends PageDetailState {}

final class PageDetailLoaded extends PageDetailState {
  PageDetailLoaded(this.page);
  final PageModel page;
}

final class PageDetailError extends PageDetailState {
  PageDetailError(this.message);
  final String message;
}
