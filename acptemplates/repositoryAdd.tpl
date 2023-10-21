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

{include file='formError'}

{if $success|isset}
	<p class="success">
		{lang}packages.page.repositoryAdd.scuccess{/lang}
	</p>
{/if}

<form method="post" action="{link application='packages' controller='RepositoryAdd'}{/link}">
	<section class="section">
		<dl{if $errorField == 'name'} class="formError"{/if}>
			<dt><label for="name">{lang}packages.page.repositoryAdd.name{/lang}</label></dt>
			<dd>
				<input type="text" name="name" id="name" value="{$name}" required>
				{if $errorField == 'name'}
					<small class="innerError">
						{if $errorType == 'tooShort'}
							{lang}packages.page.repositoryAdd.name.error.tooShort{/lang}
						{else if $errorType == 'noNumberOnStart'}
							{lang}packages.page.repositoryAdd.name.error.noNumberOnStart{/lang}
						{else if $errorType == 'wrongFormat'}
							{lang}packages.page.repositoryAdd.name.error.wrongFormat{/lang}
						{else if $errorType == 'nameTooLong'}
							{lang}packages.page.repositoryAdd.name.error.nameTooLong{/lang}
						{else if $errorType == 'alreadyUsed'}
							{lang}packages.page.repositoryAdd.name.error.alreadyUsed{/lang}
						{/if}
					</small>
				{/if}
				<small>{lang}packages.page.repositoryAdd.name.description{/lang}</small>
			</dd>
		</dl>
	</section>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{csrfToken}
	</div>
</form>

{include file='footer'}