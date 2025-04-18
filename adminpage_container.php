<!DOCTYPE html>
<html id="XF" lang="{$xf.language.language_code}" dir="{$xf.language.text_direction}"
	data-app="public"
	data-template="{$template}"
	data-container-key="{$containerKey}"
	data-content-key="{$contentKey}"
	data-logged-in="{{ $xf.visitor.user_id ? 'true' : 'false' }}"
	data-cookie-prefix="{$xf.cookie.prefix}"
	data-csrf="{{ csrf_token()|escape('js') }}"
	class="has-no-js {{ $template ? 'template-' . $template : '' }}"
	{{ $xf.runJobs ? ' data-run-jobs=""' : '' }}>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<xf:set var="$siteName" value="{$xf.options.boardTitle}" />
	<xf:set var="$h1"><xf:h1 fallback="{$siteName}" /></xf:set>
	<xf:set var="$description"><xf:description /></xf:set>

	<title><xf:title formatter="%s | %s" fallback="{$xf.options.boardTitle}" page="{$pageNumber}" /></title>

	<link rel="manifest" href="{{ base_url('webmanifest.php') }}">
	<xf:if is="property('metaThemeColor')">
		<meta name="theme-color" content="{{ parse_less_color(property('metaThemeColor')) }}" />
	</xf:if>

	<meta name="apple-mobile-web-app-title" content="{{ $xf.options.boardShortTitle ?: $xf.options.boardTitle }}">
	<xf:if is="property('publicIconUrl')">
		<link rel="apple-touch-icon" href="{{ base_url(property('publicIconUrl', true)) }}">
	<xf:elseif is="property('publicMetadataLogoUrl')" />
		<link rel="apple-touch-icon" href="{{ base_url(property('publicMetadataLogoUrl')) }}" />
	</xf:if>

	<xf:foreach loop="$head" value="$headTag">
		{$headTag}
	</xf:foreach>

	<xf:if is="!$head.meta_site_name && $siteName is not empty">
		<xf:macro template="metadata_macros" name="site_name" arg-siteName="{$siteName}" arg-output="{{ true }}" />
	</xf:if>
	<xf:if is="!$head.meta_type">
		<xf:macro template="metadata_macros" name="type" arg-type="website" arg-output="{{ true }}" />
	</xf:if>
	<xf:if is="!$head.meta_title">
		<xf:macro template="metadata_macros" name="title" arg-title="{{ page_title() ?: $siteName }}" arg-output="{{ true }}" />
	</xf:if>
	<xf:if is="!$head.meta_description && $description is not empty && $pageDescriptionMeta">
		<xf:macro template="metadata_macros" name="description" arg-description="{$description}" arg-output="{{ true }}" />
	</xf:if>
	<xf:if is="!$head.meta_share_url">
		<xf:macro template="metadata_macros" name="share_url" arg-shareUrl="{$xf.fullUri}" arg-output="{{ true }}" />
	</xf:if>
	<xf:if is="!$head.meta_image_url && property('publicMetadataLogoUrl')">
		<xf:macro template="metadata_macros" name="image_url"
			arg-imageUrl="{{ base_url(property('publicMetadataLogoUrl'), true) }}"
			arg-output="{{ true }}" />
	</xf:if>

	<xf:macro template="helper_js_global" name="head" arg-app="public" />

	<xf:if is="property('publicFaviconUrl')">
		<link rel="icon" type="image/png" href="{{ base_url(property('publicFaviconUrl'), true) }}" sizes="32x32" />
	</xf:if>
	<xf:include template="google_analytics" />
</head>
<body data-template="{$template}">

<div class="p-pageWrapper" id="top">

<xf:if contentcheck="true">
	<div class="p-staffBar">
		<div class="logo">
                    <a href="#">
                        <img src="https://vitur.io/images/logo.png" alt="Vitur">
                    </a>
                </div>
		<div class="header">
                    <a href="https://vitur.io/play" class="play"><p></p><span></span></a>
                    <ul class="navigation">
                        <li><a id="home" href="https://vitur.io"><p></p><span></span></a></li>
                        <li><a id="community" href="https://vitur.io/play"><p></p><span></span></a></li>
                        <li><a id="play" href="https://vitur.io/votenow"><p></p><span></span></a></li>
                        <li><a id="hiscores" href="https://discord.gg/jfAnGuAtqK" target="_blank"><p></p><span></span></a></li>
                        <li><a id="vote" href="https://vitur.io/hiscores/"><p></p><span></span></a></li>
                        <li><a id="donate" href=""><p></p><span></span></a></li>
                        <li><a id="account" href="https://vitur.io/store"><p></p><span></span></a></li>
                    </ul>
                </div>
			
		
		<div class="p-staffBar-inner hScroller" data-xf-init="h-scroller">
			<div class="hScroller-scroll">
			<xf:contentcheck>
				<xf:if is="$xf.visitor.is_moderator && $xf.session.unapprovedCounts.total">
					<a href="{{ link('approval-queue') }}" class="p-staffBar-link badgeContainer badgeContainer--highlighted" data-badge="{$xf.session.unapprovedCounts.total|number}">
						{{ phrase('approval_queue') }}
					</a>
				</xf:if>

				<xf:if is="$xf.visitor.is_moderator && !$xf.options.reportIntoForumId && $xf.session.reportCounts.total">
					<a href="{{ link('reports') }}"
						class="p-staffBar-link badgeContainer badgeContainer--visible {{ ($xf.session.reportCounts.total && ($xf.session.reportCounts.lastBuilt > $xf.session.reportLastRead) OR $xf.session.reportCounts.assigned) ? ' badgeContainer--highlighted' : '' }}"
						data-badge="{{ $xf.session.reportCounts.assigned ? $xf.session.reportCounts.assigned|number . ' / ' . $xf.session.reportCounts.total|number : $xf.session.reportCounts.total|number }}"
						title="{{ $xf.session.reportCounts.lastBuilt ? phrase('last_report_update:')|for_attr . ' ' . date_time($xf.session.reportCounts.lastBuilt) : '' }}">
						{{ phrase('reports') }}
					</a>
				</xf:if>

				<xf:if contentcheck="true">
					<a class="p-staffBar-link menuTrigger" data-xf-click="menu" data-xf-key="alt+m" role="button" tabindex="0" aria-expanded="false" aria-haspopup="true">{{ phrase('moderator') }}</a>
					<div class="menu" data-menu="menu" aria-hidden="true">
						<div class="menu-content">
							<h4 class="menu-header">{{ phrase('moderator_tools') }}</h4>
							<xf:contentcheck>
							<!--[XF:mod_tools_menu:top]-->
							<xf:if is="$xf.visitor.is_moderator">
								<a href="{{ link('approval-queue') }}" class="menu-linkRow">{{ phrase('approval_queue') }}</a>
							</xf:if>
							<xf:if is="$xf.visitor.is_moderator && !$xf.options.reportIntoForumId">
								<a href="{{ link('reports') }}" class="menu-linkRow" title="{{ $xf.session.reportCounts.lastBuilt ? phrase('last_report_update:')|for_attr . ' ' . date_time($xf.session.reportCounts.lastBuilt) : '' }}">{{ phrase('reports') }}</a>
							</xf:if>
							<!--[XF:mod_tools_menu:bottom]-->
							</xf:contentcheck>
						</div>
					</div>
				</xf:if>

				<xf:if is="$xf.visitor.is_admin">
					<a href="{{ base_url('admin.php') }}" class="p-staffBar-link" target="_blank">{{ phrase('admin') }}</a>
				</xf:if>
			</xf:contentcheck>
			</div>
		</div>
	</div>
</xf:if>

<xf:set var="$srcset">{{ property('publicLogoUrl2x') ? base_url(property('publicLogoUrl2x')) . ' 2x' : '' }}</xf:set>

<header class="p-header" id="header">
	<div class="p-header-inner">
		<div class="p-header-content">

			<div class="p-header-logo p-header-logo--image">
				<a href="{{ ($xf.options.logoLink && $xf.homePageUrl) ? $xf.homePageUrl : link('index') }}">
					<img src="{{ base_url(property('publicLogoUrl')) }}" srcset="{$srcset}" alt="{$xf.options.boardTitle}"
						width="{{ property('publicLogoWidth') ?: '' }}" height="{{ property('publicLogoHeight') ?: '' }}" />
				</a>
			</div>

			<xf:ad position="container_header" />
		</div>
	</div>
</header>

<xf:set var="$navHtml">
	<nav class="p-nav">
		<div class="p-nav-inner">
			<xf:button class="button--plain p-nav-menuTrigger" data-xf-click="off-canvas" data-menu=".js-headerOffCanvasMenu" tabindex="0"
				aria-label="{{ phrase('menu')|for_attr }}">
				<i aria-hidden="true"></i>
			</xf:button>

			<div class="p-nav-smallLogo">
				<a href="{{ ($xf.options.logoLink && $xf.homePageUrl) ? $xf.homePageUrl : link('index') }}">
					<img src="{{ base_url(property('publicLogoUrl')) }}" srcset="{$srcset}" alt="{$xf.options.boardTitle}"
						width="{{ property('publicLogoWidth') ?: '' }}" height="{{ property('publicLogoHeight') ?: '' }}" />
				</a>
			</div>

			<div class="p-nav-scroller hScroller" data-xf-init="h-scroller" data-auto-scroll=".p-navEl.is-selected">
				<div class="hScroller-scroll">
					<ul class="p-nav-list js-offCanvasNavSource">
					<xf:foreach loop="$navTree" key="$navSection" value="$navEntry" i="$i" if="{{ $navSection != $xf.app.defaultNavigationId }}">
						<li>
							<xf:macro name="nav_entry"
								arg-navId="{$navSection}"
								arg-nav="{$navEntry}"
								arg-selected="{{ $navSection == $pageSection }}"
								arg-shortcut="{$i}" />
						</li>
					</xf:foreach>
					</ul>
				</div>
			</div>

			<div class="p-nav-opposite">
				<div class="p-navgroup p-account {{ $xf.visitor.user_id ? 'p-navgroup--member' : 'p-navgroup--guest' }}">
					<xf:if is="$xf.visitor.user_id">
						<xf:if is="$xf.visitor.user_state == 'rejected' OR $xf.visitor.user_state == 'disabled'">
							<a href="{{ link('account') }}"
								class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--user">
								<xf:avatar user="$xf.visitor" size="xxs" href="" title="" />
								<span class="p-navgroup-linkText">{$xf.visitor.username}</span>
							</a>

							<a href="{{ link('logout', null, {'t': csrf_token()}) }}" class="p-navgroup-link">
								<span class="p-navgroup-linkText">{{ phrase('log_out') }}</span>
							</a>
						<xf:else />
							<a href="{{ link('account') }}"
								class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--user"
								data-xf-click="menu"
								data-xf-key="{{ phrase('shortcut.visitor_menu')|for_attr }}"
								data-menu-pos-ref="< .p-navgroup"
								title="{$xf.visitor.username}"
								aria-expanded="false"
								aria-haspopup="true">
								<xf:avatar user="$xf.visitor" size="xxs" href="" title="" />
								<span class="p-navgroup-linkText">{$xf.visitor.username}</span>
							</a>
							<div class="menu menu--structural menu--wide menu--account" data-menu="menu" aria-hidden="true"
								data-href="{{ link('account/visitor-menu') }}"
								data-load-target=".js-visitorMenuBody">
								<div class="menu-content js-visitorMenuBody">
									<div class="menu-row">
										{{ phrase('loading...') }}
									</div>
								</div>
							</div>

							<a href="{{ link('conversations') }}"
								class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--conversations js-badge--conversations badgeContainer{{ $xf.visitor.conversations_unread ? ' badgeContainer--highlighted' : '' }}"
								data-badge="{$xf.visitor.conversations_unread|number}"
								data-xf-click="menu"
								data-xf-key="{{ phrase('shortcut.conversations_menu')|for_attr }}"
								data-menu-pos-ref="< .p-navgroup"
								title="{{ phrase('conversations')|for_attr }}"
								aria-label="{{ phrase('conversations')|for_attr }}"
								aria-expanded="false"
								aria-haspopup="true">
								<i aria-hidden="true"></i>
								<span class="p-navgroup-linkText">{{ phrase('nav_inbox') }}</span>
							</a>
							<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true"
								data-href="{{ link('conversations/popup') }}"
								data-nocache="true"
								data-load-target=".js-convMenuBody">
								<div class="menu-content">
									<h3 class="menu-header">{{ phrase('conversations') }}</h3>
									<div class="js-convMenuBody">
										<div class="menu-row">{{ phrase('loading...') }}</div>
									</div>
									<div class="menu-footer menu-footer--split">
										<div class="menu-footer-main">
											<ul class="listInline listInline--bullet">
												<li><a href="{{ link('conversations') }}">{{ phrase('show_all') }}</a></li>
												<xf:if is="$xf.visitor.canStartConversation()">
													<li><a href="{{ link('conversations/add') }}">{{ phrase('start_new_conversation') }}</a></li>
												</xf:if>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<a href="{{ link('account/alerts') }}"
								class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--alerts js-badge--alerts badgeContainer{{ $xf.visitor.alerts_unviewed ? ' badgeContainer--highlighted' : '' }}"
								data-badge="{$xf.visitor.alerts_unviewed|number}"
								data-xf-click="menu"
								data-xf-key="{{ phrase('shortcut.alerts_menu')|for_attr }}"
								data-menu-pos-ref="< .p-navgroup"
								title="{{ phrase('alerts')|for_attr }}"
								aria-label="{{ phrase('alerts')|for_attr }}"
								aria-expanded="false"
								aria-haspopup="true">
								<i aria-hidden="true"></i>
								<span class="p-navgroup-linkText">{{ phrase('nav_alerts') }}</span>
							</a>
							<div class="menu menu--structural menu--medium" data-menu="menu" aria-hidden="true"
								data-href="{{ link('account/alerts-popup') }}"
								data-nocache="true"
								data-load-target=".js-alertsMenuBody">
								<div class="menu-content">
									<h3 class="menu-header">{{ phrase('alerts') }}</h3>
									<div class="js-alertsMenuBody">
										<div class="menu-row">{{ phrase('loading...') }}</div>
									</div>
									<div class="menu-footer menu-footer--split">
										<div class="menu-footer-main">
											<ul class="listInline listInline--bullet">
												<li><a href="{{ link('account/alerts') }}">{{ phrase('show_all') }}</a></li>
												<li><a href="{{ link('account/alerts/mark-read') }}" class="js-alertsMarkRead">{{ phrase('mark_read') }}</a></li>
												<li><a href="{{ link('account/preferences') }}">{{ phrase('preferences') }}</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</xf:if>
					<xf:else />
						<a href="{{ link('login') }}" class="p-navgroup-link p-navgroup-link--textual p-navgroup-link--logIn"
							data-xf-click="overlay" data-follow-redirects="on">
							<span class="p-navgroup-linkText">{{ phrase('log_in') }}</span>
						</a>
						<xf:if is="$xf.options.registrationSetup.enabled">
							<a href="{{ link('register') }}" class="p-navgroup-link p-navgroup-link--textual p-navgroup-link--register"
								data-xf-click="overlay" data-follow-redirects="on">
								<span class="p-navgroup-linkText">{{ phrase('register') }}</span>
							</a>
						</xf:if>
					</xf:if>
				</div>

				<div class="p-navgroup p-discovery{{ !$xf.visitor.canSearch() ? ' p-discovery--noSearch' : '' }}">
					<a href="{{ link('whats-new') }}"
						class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--whatsnew"
						aria-label="{{ phrase('whats_new')|for_attr }}"
						title="{{ phrase('whats_new')|for_attr }}">
						<i aria-hidden="true"></i>
						<span class="p-navgroup-linkText">{{ phrase('whats_new') }}</span>
					</a>

					<xf:if is="$xf.visitor.canSearch()">
						<a href="{{ link('search') }}"
							class="p-navgroup-link p-navgroup-link--iconic p-navgroup-link--search"
							data-xf-click="menu"
							data-xf-key="{{ phrase('shortcut.search_menu')|for_attr }}"
							aria-label="{{ phrase('search')|for_attr }}"
							aria-expanded="false"
							aria-haspopup="true"
							title="{{ phrase('search')|for_attr }}">
							<i aria-hidden="true"></i>
							<span class="p-navgroup-linkText">{{ phrase('search') }}</span>
						</a>
						<div class="menu menu--structural menu--wide" data-menu="menu" aria-hidden="true">
							<form action="{{ link('search/search') }}" method="post"
								class="menu-content"
								data-xf-init="quick-search">

								<h3 class="menu-header">{{ phrase('search') }}</h3>
								<!--[XF:search_menu:above_input]-->
								<div class="menu-row">
									<xf:if is="$searchConstraints">
										<div class="inputGroup inputGroup--joined">
											<xf:textbox name="keywords"
												placeholder="{{ phrase('search...') }}"
												aria-label="{{ phrase('search') }}"
												data-menu-autofocus="true" />
											<xf:select name="constraints"
												class="js-quickSearch-constraint"
												aria-label="{{ phrase('search_within') }}">

												<xf:option value="">{{ phrase('everywhere') }}</xf:option>
												<xf:foreach loop="$searchConstraints" key="$constraintName" value="$constraint">
													<xf:option value="{$constraint|json}">{$constraintName}</xf:option>
												</xf:foreach>
											</xf:select>
										</div>
									<xf:else />
										<xf:textbox name="keywords"
											placeholder="{{ phrase('search...') }}"
											aria-label="{{ phrase('search') }}"
											data-menu-autofocus="true" />
									</xf:if>
								</div>

								<!--[XF:search_menu:above_title_only]-->
								<div class="menu-row">
									<xf:checkbox standalone="true">
										<xf:option name="c[title_only]">
											<xf:label>
												{{ phrase('search_titles_only') }}

												<xf:if is="$xf.options.enableTagging">
													<span tabindex="0" role="button"
														data-xf-init="tooltip" data-trigger="hover focus click" title="{{ phrase('tags_will_also_be_searched')|for_attr }}">

														<xf:fa icon="far fa-question-circle" class="u-muted u-smaller" />
													</span>
												</xf:if>
											</xf:label>
										</xf:option>
									</xf:checkbox>
								</div>
								<!--[XF:search_menu:above_member]-->
								<div class="menu-row">
									<div class="inputGroup">
										<span class="inputGroup-text" id="ctrl_search_menu_by_member">{{ phrase('by:') }}</span>
										<input type="text" class="input" name="c[users]" data-xf-init="auto-complete" placeholder="{{ phrase('member') }}" aria-labelledby="ctrl_search_menu_by_member" />
									</div>
								</div>
								<div class="menu-footer">
									<span class="menu-footer-controls">
										<xf:button type="submit" class="button--primary" icon="search" />
										<xf:button href="{{ link('search') }}">{{ phrase('advanced_search...') }}</xf:button>
									</span>
								</div>

								<xf:csrf />
							</form>
						</div>
					</xf:if>
				</div>
			</div>
		</div>
	</nav>
</xf:set>
<xf:set var="$subNavHtml">
	<xf:if is="$selectedNavChildren is not empty">
		<div class="p-sectionLinks">
			<div class="p-sectionLinks-inner hScroller" data-xf-init="h-scroller">
				<div class="hScroller-scroll">
					<ul class="p-sectionLinks-list">
					<xf:foreach loop="$selectedNavChildren" key="$navId" value="$navEntry" i="$i">
						<li>
							<xf:macro name="nav_entry" arg-navId="{$navId}" arg-nav="{$navEntry}" arg-shortcut="alt+{$i}" />
						</li>
					</xf:foreach>
					</ul>
				</div>
			</div>
		</div>
	<xf:elseif is="{$selectedNavEntry}" />
		<div class="p-sectionLinks p-sectionLinks--empty"></div>
	</xf:if>
</xf:set>

<xf:if is="property('publicNavSticky') == 'primary'">
	<div class="p-navSticky p-navSticky--primary" data-xf-init="sticky-header">
		{$navHtml|raw}
	</div>
	{$subNavHtml|raw}
<xf:elseif is="property('publicNavSticky') == 'all'" />
	<div class="p-navSticky p-navSticky--all" data-xf-init="sticky-header">
		{$navHtml|raw}
		{$subNavHtml|raw}
	</div>
<xf:else />
	{$navHtml|raw}
	{$subNavHtml|raw}
</xf:if>

<div class="offCanvasMenu offCanvasMenu--nav js-headerOffCanvasMenu" data-menu="menu" aria-hidden="true" data-ocm-builder="navigation">
	<div class="offCanvasMenu-backdrop" data-menu-close="true"></div>
	<div class="offCanvasMenu-content">
		<div class="offCanvasMenu-header">
			{{ phrase('menu') }}
			<a class="offCanvasMenu-closer" data-menu-close="true" role="button" tabindex="0" aria-label="{{ phrase('close')|for_attr }}"></a>
		</div>
		<xf:if is="$xf.visitor.user_id">
			<div class="p-offCanvasAccountLink">
				<div class="offCanvasMenu-linkHolder">
					<a href="{{ link('account') }}" class="offCanvasMenu-link">
						<xf:avatar user="$xf.visitor" size="xxs" href="" />
						{$xf.visitor.username}
					</a>
				</div>
				<hr class="offCanvasMenu-separator" />
			</div>
		<xf:else />
			<div class="p-offCanvasRegisterLink">
				<div class="offCanvasMenu-linkHolder">
					<a href="{{ link('login') }}" class="offCanvasMenu-link" data-xf-click="overlay" data-menu-close="true">
						{{ phrase('log_in') }}
					</a>
				</div>
				<hr class="offCanvasMenu-separator" />
				<xf:if is="$xf.options.registrationSetup.enabled">
					<div class="offCanvasMenu-linkHolder">
						<a href="{{ link('register') }}" class="offCanvasMenu-link" data-xf-click="overlay" data-menu-close="true">
							{{ phrase('register') }}
						</a>
					</div>
					<hr class="offCanvasMenu-separator" />
				</xf:if>
			</div>
		</xf:if>
		<div class="js-offCanvasNavTarget"></div>
		<div class="offCanvasMenu-installBanner js-installPromptContainer" style="display: none;" data-xf-init="install-prompt">
			<div class="offCanvasMenu-installBanner-header">{{ phrase('install_app') }}</div>
			<xf:button class="js-installPromptButton">{{ phrase('install') }}</xf:button>
		</div>
	</div>
</div>

<div class="p-body">
	<div class="p-body-inner">
		<!--XF:EXTRA_OUTPUT-->

		<xf:if is="$notices.block">
			<xf:macro template="notice_macros" name="notice_list" arg-type="block" arg-notices="{$notices.block}" />
		</xf:if>

		<xf:if is="$notices.scrolling">
			<xf:macro template="notice_macros" name="notice_list" arg-type="scrolling" arg-notices="{$notices.scrolling}" />
		</xf:if>

		<xf:ad position="container_breadcrumb_top_above" />
		<xf:macro name="breadcrumbs"
			arg-breadcrumbs="{$breadcrumbs}"
			arg-navTree="{$navTree}"
			arg-selectedNavEntry="{$selectedNavEntry}" />
		<xf:ad position="container_breadcrumb_top_below" />

		<xf:macro template="browser_warning_macros" name="javascript" />
		<xf:macro template="browser_warning_macros" name="browser" />

		<xf:if is="$headerHtml is not empty">
			<div class="p-body-header">
				{$headerHtml|raw}
			</div>
		<xf:elseif contentcheck="true" />
			<div class="p-body-header">
			<xf:contentcheck>
				<xf:if contentcheck="true">
					<div class="p-title {{ $noH1 ? 'p-title--noH1' : '' }}">
					<xf:contentcheck>
						<xf:if is="!$noH1">
							<h1 class="p-title-value">{$h1}</h1>
						</xf:if>
						<xf:if contentcheck="true">
							<div class="p-title-pageAction"><xf:contentcheck><xf:pageaction /></xf:contentcheck></div>
						</xf:if>
					</xf:contentcheck>
					</div>
				</xf:if>

				<xf:if is="$description is not empty">
					<div class="p-description">{$description}</div>
				</xf:if>
			</xf:contentcheck>
			</div>
		</xf:if>

		<div class="p-body-main {{ $sidebar ? 'p-body-main--withSidebar' : '' }} {{ $sideNav ? 'p-body-main--withSideNav' : '' }}">
			<xf:if is="$sideNav">
				<div class="p-body-sideNavCol"></div>
			</xf:if>
			<div class="p-body-contentCol"></div>
			<xf:if is="$sidebar">
				<div class="p-body-sidebarCol"></div>
			</xf:if>

			<xf:if is="$sideNav">
				<div class="p-body-sideNav">
					<div class="p-body-sideNavTrigger">
						<xf:button class="button--link" data-xf-click="off-canvas" data-menu="#js-SideNavOcm">
							{{ $sideNavTitle ?: phrase('navigation') }}
						</xf:button>
					</div>
					<div class="p-body-sideNavInner" data-ocm-class="offCanvasMenu offCanvasMenu--blocks" id="js-SideNavOcm" data-ocm-builder="sideNav">
						<div data-ocm-class="offCanvasMenu-backdrop" data-menu-close="true"></div>
						<div data-ocm-class="offCanvasMenu-content">
							<div class="p-body-sideNavContent">
								<xf:ad position="container_sidenav_above" />
								<xf:foreach loop="$sideNav" value="$sideNavHtml">
									{$sideNavHtml}
								</xf:foreach>
								<xf:ad position="container_sidenav_below" />
							</div>
						</div>
					</div>
				</div>
			</xf:if>

			<div class="p-body-content">
				<xf:ad position="container_content_above" />
				<div class="p-body-pageContent">{$content|raw}</div>
				<xf:ad position="container_content_below" />
			</div>

			<xf:if is="$sidebar">
				<div class="p-body-sidebar">
					<xf:ad position="container_sidebar_above" />
					<xf:foreach loop="$sidebar" value="$sidebarHtml">
						{$sidebarHtml}
					</xf:foreach>
					<xf:ad position="container_sidebar_below" />
				</div>
			</xf:if>
		</div>

		<xf:ad position="container_breadcrumb_bottom_above" />
		<xf:macro name="breadcrumbs"
			arg-breadcrumbs="{$breadcrumbs}"
			arg-navTree="{$navTree}"
			arg-selectedNavEntry="{$selectedNavEntry}"
			arg-variant="bottom" />
		<xf:ad position="container_breadcrumb_bottom_below" />
	</div>
</div>

<footer class="p-footer" id="footer">
	<div class="p-footer-inner">

		<div class="p-footer-row">
			<xf:if contentcheck="true">
				<div class="p-footer-row-main">
					<ul class="p-footer-linkList">
					<xf:contentcheck>
						<xf:if is="$xf.visitor.canChangeStyle()">
							<li><a href="{{ link('misc/style') }}" data-xf-click="overlay"
								data-xf-init="tooltip" title="{{ phrase('style_chooser')|for_attr }}" rel="nofollow">
								<xf:fa icon="fa-paint-brush" /> {$xf.style.title}
							</a></li>
						</xf:if>
						<xf:if is="$xf.visitor.canChangeLanguage()">
							<li><a href="{{ link('misc/language') }}" data-xf-click="overlay"
								data-xf-init="tooltip" title="{{ phrase('language_chooser')|for_attr }}" rel="nofollow">
								<xf:fa icon="fa-globe" /> {$xf.language.title}</a></li>
						</xf:if>
					</xf:contentcheck>
					</ul>
				</div>
			</xf:if>
			<div class="p-footer-row-opposite">
				<ul class="p-footer-linkList">
					<xf:if is="$xf.visitor.canUseContactForm()">
						<xf:if is="$xf.contactUrl">
							<li><a href="{$xf.contactUrl}" data-xf-click="{{ ($xf.options.contactUrl.overlay OR $xf.options.contactUrl.type == 'default') ? 'overlay' : '' }}">{{ phrase('contact_us') }}</a></li>
						</xf:if>
					</xf:if>

					<xf:if is="$xf.tosUrl">
						<li><a href="{$xf.tosUrl}">{{ phrase('terms_and_rules') }}</a></li>
					</xf:if>

					<xf:if is="$xf.privacyPolicyUrl">
						<li><a href="{$xf.privacyPolicyUrl}">{{ phrase('privacy_policy') }}</a></li>
					</xf:if>

					<xf:if is="$xf.helpPageCount">
						<li><a href="{{ link('help') }}">{{ phrase('help') }}</a></li>
					</xf:if>

					<xf:if is="$xf.homePageUrl">
						<li><a href="{$xf.homePageUrl}">{{ phrase('home') }}</a></li>
					</xf:if>

					<li><a href="{{ link('forums/index.rss', '-') }}" target="_blank" class="p-footer-rssLink" title="{{ phrase('rss')|for_attr }}"><span aria-hidden="true"><xf:fa icon="fa-rss" /><span class="u-srOnly">{{ phrase('rss') }}</span></span></a></li>
				</ul>
			</div>
		</div>

		<xf:if contentcheck="true">
			<div class="p-footer-copyright">
			<xf:contentcheck>
				<xf:copyright />
				{{ phrase('extra_copyright') }}
			</xf:contentcheck>
			</div>
		</xf:if>

		<xf:if contentcheck="true">
			<div class="p-footer-debug">
			<xf:contentcheck>
				<xf:macro template="debug_macros" name="debug"
					arg-controller="{$controller}"
					arg-action="{$actionMethod}"
					arg-template="{$template}" />
			</xf:contentcheck>
			</div>
		</xf:if>
	</div>
</footer>

</div> <!-- closing p-pageWrapper -->

<div class="u-bottomFixer js-bottomFixTarget">
	<xf:if is="$notices.floating">
		<xf:macro template="notice_macros" name="notice_list" arg-type="floating" arg-notices="{$notices.floating}" />
	</xf:if>
	<xf:if is="$notices.bottom_fixer">
		<xf:macro template="notice_macros" name="notice_list" arg-type="bottom_fixer" arg-notices="{$notices.bottom_fixer}" />
	</xf:if>
</div>

<xf:if is="property('scrollJumpButtons')">
	<div class="u-scrollButtons js-scrollButtons" data-trigger-type="{{ property('scrollJumpButtons') }}">
		<xf:button href="#top" class="button--scroll" data-xf-click="scroll-to"><xf:fa icon="fa-arrow-up" /><span class="u-srOnly">{{ phrase('top') }}</span></xf:button>
		<xf:if is="property('scrollJumpButtons') != 'up'">
			<xf:button href="#footer" class="button--scroll" data-xf-click="scroll-to"><xf:fa icon="fa-arrow-down" /><span class="u-srOnly">{{ phrase('bottom') }}</span></xf:button>
		</xf:if>
	</div>
</xf:if>

<xf:macro template="helper_js_global" name="body" arg-app="public" arg-jsState="{$jsState}" />

<xf:if is="count($xf.reactionsActive) > 1 AND $xf.visitor.user_id">
	<script type="text/template" id="xfReactTooltipTemplate">
		<div class="tooltip-content-inner">
			<div class="reactTooltip">
				<xf:foreach loop="$xf.reactionsActive" key="$reactionId" value="$reaction">
					<xf:reaction id="{$reactionId}" tooltip="true" />
				</xf:foreach>
			</div>
		</div>
	</script>
</xf:if>

{$ldJsonHtml|raw}

</body>
</html>

<xf:macro name="nav_entry" arg-navId="!" arg-nav="!" arg-selected="{{ false }}" arg-shortcut="">
	<div class="p-navEl {{ $selected ? 'is-selected' : '' }}" {{ $nav.children ? 'data-has-children="true"' : '' }}>
		<xf:if is="$nav.href">

			<xf:macro name="nav_link"
				arg-navId="{$navId}"
				arg-nav="{$nav}"
				arg-class="p-navEl-link {{ $nav.children ? 'p-navEl-link--splitMenu' : '' }}"
				arg-shortcut="{{ $nav.children ? false : $shortcut }}" />

			<xf:if is="$nav.children"><a data-xf-key="{$shortcut}"
				data-xf-click="menu"
				data-menu-pos-ref="< .p-navEl"
				class="p-navEl-splitTrigger"
				role="button"
				tabindex="0"
				aria-label="{{ phrase('toggle_expanded')|for_attr }}"
				aria-expanded="false"
				aria-haspopup="true"></a></xf:if>

		<xf:elseif is="$nav.children" /><a data-xf-key="{$shortcut}"
			data-xf-click="menu"
			data-menu-pos-ref="< .p-navEl"
			class="p-navEl-linkHolder"
			role="button"
			tabindex="0"
			aria-expanded="false"
			aria-haspopup="true">
			<xf:macro name="nav_link"
				arg-navId="{$navId}"
				arg-nav="{$nav}"
				arg-class="p-navEl-link p-navEl-link--menuTrigger" />
		</a>

		<xf:else />

			<xf:macro name="nav_link"
				arg-navId="{$navId}"
				arg-nav="{$nav}"
				arg-class="p-navEl-link"
				arg-shortcut="{$shortcut}" />

		</xf:if>
		<xf:if is="$nav.children">
			<div class="menu menu--structural" data-menu="menu" aria-hidden="true">
				<div class="menu-content">
					<xf:foreach loop="$nav.children" key="$childNavId" value="$child">
						<xf:macro name="nav_menu_entry"
							arg-navId="{$childNavId}"
							arg-nav="{$child}" />
					</xf:foreach>
				</div>
			</div>
		</xf:if>
	</div>
</xf:macro>

<xf:macro name="nav_link" arg-navId="!" arg-nav="!" arg-class="" arg-titleHtml="" arg-shortcut="{{ false }}">
	<xf:set var="$tag" value="{{ $nav.href ? 'a' : 'span' }}" />
	<{$tag} {{ $nav.href ? 'href="' . $nav.href . '"' : '' }}
		class="{{ trim($class) }} {$nav.attributes.class}"
		{{ attributes($nav.attributes, ['class']) }}
		{{ $shortcut !== false ? 'data-xf-key="' . $shortcut . '"' : '' }}
		data-nav-id="{$navId}"><xf:if is="$nav.icon"><xf:fa icon="{$nav.icon}" /> </xf:if>{{ $titleHtml ? $titleHtml|raw : $nav.title }}<xf:if is="$nav.counter"> <span class="badge badge--highlighted">{$nav.counter|number}</span></xf:if></{$tag}>
</xf:macro>

<xf:macro name="nav_menu_entry" arg-navId="!" arg-nav="!" arg-depth="0">
	<xf:macro name="nav_link"
		arg-navId="{$navId}"
		arg-nav="{$nav}"
		arg-class="menu-linkRow u-indentDepth{$depth} js-offCanvasCopy" />
	<xf:if is="$nav.children">
		<xf:foreach loop="$nav.children" key="$childNavId" value="$child">
			<xf:macro name="nav_menu_entry"
				arg-navId="{$childNavId}"
				arg-nav="{$child}"
				arg-depth="{{ $depth + 1 }}" />
		</xf:foreach>
		<xf:if is="$depth == 0">
			<hr class="menu-separator" />
		</xf:if>
	</xf:if>
</xf:macro>

<xf:macro name="breadcrumbs" arg-breadcrumbs="!" arg-navTree="!" arg-selectedNavEntry="{{ null }}" arg-variant="">
	<xf:if contentcheck="true">
		<ul class="p-breadcrumbs {{ $variant ? 'p-breadcrumbs--' . $variant : '' }}"
			itemscope itemtype="https://schema.org/BreadcrumbList">
		<xf:contentcheck>
			<xf:set var="$position" value="{{ 0 }}" />

			<xf:set var="$rootBreadcrumb" value="{$navTree.{$xf.options.rootBreadcrumb}}" />
			<xf:set var="$rootBreadcrumbHref" value="{{ $rootBreadcrumb.href|substr(-1) == '/'
					? $rootBreadcrumb.href
					: $rootBreadcrumb.href . '/'
				}}" />

			<xf:if is="$rootBreadcrumb AND $rootBreadcrumbHref != $xf.uri AND $rootBreadcrumbHref != $xf.fullUri">
				<xf:set var="$position" value="{{ $position + 1 }}" />
				<xf:macro name="crumb"
					arg-position="{$position}"
					arg-href="{$rootBreadcrumb.href}"
					arg-value="{$rootBreadcrumb.title}" />
			</xf:if>

			<xf:if is="$selectedNavEntry AND $selectedNavEntry.href AND $selectedNavEntry.href != $xf.uri AND $selectedNavEntry.href != $xf.fullUri AND $selectedNavEntry.href != $rootBreadcrumbHref">
				<xf:set var="$position" value="{{ $position + 1 }}" />
				<xf:macro name="crumb"
					arg-position="{$position}"
					arg-href="{$selectedNavEntry.href}"
					arg-value="{$selectedNavEntry.title}" />
			</xf:if>
			<xf:foreach loop="$breadcrumbs" value="$breadcrumb" if="$breadcrumb.href != $xf.uri AND $breadcrumb.href != $xf.fullUri">
				<xf:set var="$position" value="{{ $position + 1 }}" />
				<xf:macro name="crumb"
					arg-position="{$position}"
					arg-href="{$breadcrumb.href}"
					arg-value="{$breadcrumb.value}" />
			</xf:foreach>

		</xf:contentcheck>
		</ul>
	</xf:if>
</xf:macro>

<xf:macro name="crumb" arg-href="!" arg-value="!" arg-position="{{ 0 }}">
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<a href="{$href}" itemprop="item">
			<span itemprop="name">{$value}</span>
		</a>
		<xf:if is="$position"><meta itemprop="position" content="{$position}" /></xf:if>
	</li>
</xf:macro>