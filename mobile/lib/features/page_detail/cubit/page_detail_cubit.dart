import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:cms_mobile/features/page_detail/cubit/page_detail_state.dart';
import 'package:cms_mobile/features/home/data/repositories/page_repository.dart';

class PageDetailCubit extends Cubit<PageDetailState> {
  PageDetailCubit(this._repository) : super(PageDetailInitial());

  final PageRepository _repository;

  Future<void> loadPage(String slug) async {
    emit(PageDetailLoading());
    try {
      final page = await _repository.getPage(slug);
      emit(PageDetailLoaded(page));
    } catch (e) {
      emit(PageDetailError('Failed to load page. Please try again.'));
    }
  }
}
