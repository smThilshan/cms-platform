import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:cms_mobile/core/api/dio_client.dart';
import 'package:cms_mobile/core/router/app_router.dart';
import 'package:cms_mobile/core/theme/app_theme.dart';
import 'package:cms_mobile/features/home/cubit/home_cubit.dart';
import 'package:cms_mobile/features/home/data/repositories/page_repository.dart';

Future<void> main() async {
  await dotenv.load();
  runApp(const CmsApp());
}

class CmsApp extends StatelessWidget {
  const CmsApp({super.key});

  @override
  Widget build(BuildContext context) {
    final pageRepository = PageRepository(DioClient.instance);

    return RepositoryProvider.value(
      value: pageRepository,
      child: BlocProvider(
        create: (_) => HomeCubit(pageRepository),
        child: MaterialApp.router(
          title: 'CMS Platform',
          debugShowCheckedModeBanner: false,
          theme: AppTheme.light,
          routerConfig: AppRouter.router,
        ),
      ),
    );
  }
}
