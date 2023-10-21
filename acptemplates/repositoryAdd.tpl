{include file='header' pageTitle='packages.page.repositoryAdd.title'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}packages.page.repositoryAdd.title{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link application='packages' controller='RepositoryList'}{/link}" class="button">{icon name='list' size=16} <span>{lang}packages.acp.menu.link.package.repository.list{/lang}</span></a></li>
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{@$form->getHtml()}

{include file='footer'}