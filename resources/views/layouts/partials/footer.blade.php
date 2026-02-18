<footer class="main-footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="footer-left">
                    {{-- Copyright --}}
                    <div class="copyright">
                        &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}.
                        <span class="d-none d-md-inline">Все права защищены.</span>
                    </div>

                    {{-- Version --}}
                    @if(config('app.version'))
                        <div class="version ms-3 d-none d-md-inline">
                            <span class="badge bg-light text-dark">v{{ config('app.version') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="footer-right">
                    {{-- Links --}}
                    <nav class="footer-nav">
                        <ul class="nav">
                            @foreach($footerLinks ?? [] as $link)
                                <li class="nav-item">
                                    <a
                                        href="{{ $link['url'] }}"
                                        class="nav-link"
                                        @if($link['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach

                            {{-- Language selector --}}
                            @if(config('app.multilingual'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                       data-bs-toggle="dropdown">
                                        {{ strtoupper(app()->getLocale()) }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach(config('app.locales') as $locale => $name)
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    {{ $name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </nav>

                    {{-- Back to top --}}
                    <button class="btn btn-link btn-back-top" id="backToTop">
                        <x-main-icon name="arrow-up" set="heroicon" size="sm"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
</footer>
