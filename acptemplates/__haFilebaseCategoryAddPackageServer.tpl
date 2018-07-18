{if $packagesFilebaseAdd|isset && $packagesFilebaseAdd && $__wcf->session->getPermission('admin.packages.canManageRepository')}
	<section class="section">
		<h2 class="sectionTitle">Paket-Server</h2>
		
		<dl>
			<dt>Kategorie soll in Paket-Server aufgenommen werden</dt>
			<dd>
				<ol class="flexibleButtonGroup optionTypeBoolean">
					<li>
						<input type="radio" id="post_title_yes"{if $packageServer == 1} checked=""{/if} name="values[packageServer]" value="1" class="jsEnablesOptions" data-is-boolean="true" data-disable-options="[ ]" data-enable-options="[ ]">
						<label for="post_title_yes" class="green"><span class="icon icon16 fa-check"></span> {lang}wcf.acp.option.type.boolean.yes{/lang}</label>
					</li>
					<li>
						<input type="radio" id="post_title_no"{if $packageServer == 0} checked=""{/if} name="values[packageServer]" value="0" class="jsEnablesOptions" data-is-boolean="true" data-disable-options="[ ]" data-enable-options="[ ]">
						<label for="post_title_no" class="red"><span class="icon icon16 fa-times"></span> {lang}wcf.acp.option.type.boolean.no{/lang}</label>
					</li>
				</ol>
				<small>Wenn die Option aktiviert ist, wird diese Kategorie in den Paketserver mit aufgenommen.</small>
			</dd>
		</dl>
		<dl{if $errorField == 'repository'} class="formError"{/if}>
			<dd>
				<dt>Paket-Server-Repository</dt>
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
								Es muss ein Repository ausgewählt sein.
							{else if $errorType == 'notExist'}
								Das ausgewählte Repository existiert nicht.
							{/if}
						</small>
					{/if}
				</dd>
			</dd>
		</dl>
	</section>
{/if}