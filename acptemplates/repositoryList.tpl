{include file='header' pageTitle='packages.acp.page.repository_list.title'}

<script data-relocate="true">
	$(function() {
		new WCF.Action.Delete('packages\\data\\repository\\RepositoryAction', $('.jsRow'));
	});
</script>

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">Repositorys</h1>
	</div>
	
	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link application='packages' controller='RepositoryAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>Repository hinzufügen</span></a></li>
			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}
			{pages print=true assign=pagesLinks application='packages' controller="RepositoryList" link="pageNo=%d"}
		{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>URL</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$objects item=object}
					<tr class="jsRow">
						<td class="columnIcon">
							<span class="icon icon16 fa-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$object->repositoryID}" data-confirm-message="Repository wirklich löschen?"></span>
							{event name='rowButtons'}
						</td>
						<td class="columnID">{#$object->repositoryID}</td>
						<td class="columnText">{$object->name}</td>
						<td class="columnText">TODO: URL einfügen</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}