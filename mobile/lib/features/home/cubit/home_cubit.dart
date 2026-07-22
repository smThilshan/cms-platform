import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:cms_mobile/features/home/cubit/home_state.dart';
import 'package:cms_mobile/features/home/data/repositories/page_repository.dart';

class HomeCubit extends Cubit<HomeState> {
  HomeCubit(this._repository) : super(HomeInitial());

  final PageRepository _repository;

  Future<void> loadPages() async {
    emit(HomeLoading());
    try {
      final pages = await _repository.getPages();
      emit(HomeLoaded(pages));
    } catch (e) {
      emit(HomeError('Failed to load pages. Please try again.'));
    }
  }
}
