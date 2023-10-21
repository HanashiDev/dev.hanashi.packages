{if $packagesFilebaseAdd|isset && $packagesFilebaseAdd && $__wcf->session->getPermission('admin.packages.canManageRepository')}
	<section class="section">
		<h2 class="sectionTitle">{lang}packages.page.filebaseCategoryAdd.title{/lang}</h2>
		
		<dl>
			<dt>{lang}packages.page.filebaseCategoryAdd.packageServer{/lang}</dt>
			<dd>
				<ol class="flexibleButtonGroup optionTypeBoolean">
					<li>
						<input type="radio" id="post_title_yes"{if $packageServer == 1} checked=""{/if} name="values[packageServer]" value="1" class="jsEnablesOptions" data-is-boolean="true" data-disable-options="[ ]" data-enable-options="[ ]">
						<label for="post_title_yes" class="green">{icon name='check' size=16} {lang}wcf.acp.option.type.boolean.yes{/lang}</label>
					</li>
					<li>
						<input type="radio" id="post_title_no"{if $packageServer == 0} checked=""{/if} name="values[packageServer]" value="0" class="jsEnablesOptions" data-is-boolean="true" data-disable-options="[ ]" data-enable-options="[ ]">
						<label for="post_title_no" class="red">{icon name='times' size=16} {lang}wcf.acp.option.type.boolean.no{/lang}</label>
					</li>
				</ol>
				<small>{lang}packages.page.filebaseCategoryAdd.packageServer.description{/lang}</small>
			</dd>
		</dl>
		<dl{if $errorField == 'repository'} class="formError"{/if}>
			<dd>
				<dt>{lang}packages.page.filebaseCategoryAdd.repository{/lang}</dt>
				<dd>
					<select name="repository">
						<option></option>
						{foreach from=$repositoryList item=repository}
							<option value="{@$repository->repositoryID}"{if $repositoryID == $repository->repositoryID} selected=""{/if}>{$repository->name}</option>
						{/foreach}
					</select>
					{if $errorField == 'repository'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}packages.page.filebaseCategoryAdd.repository.error.empty{/lang}
							{else if $errorType == 'notExist'}
								{lang}packages.page.filebaseCategoryAdd.repository.error.notExist{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dd>
		</dl>
	</section>
{/if}
