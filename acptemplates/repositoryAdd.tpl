{include file='header' pageTitle='packages.acp.page.repository_add.title'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">Repository hinzufügen</h1>
	</div>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">
		Das Repository wurde erfolgreich angelegt. Du kannst es nun in den Filebase-Kategorien auswählen.
	</p>
{/if}

<form method="post" action="{link application='packages' controller='RepositoryAdd'}{/link}">
	<section class="section">
		<dl{if $errorField == 'name'} class="formError"{/if}>
			<dt><label for="name">Repository-Name</label></dt>
			<dd>
				<input type="text" name="name" id="name" value="{$name}" required>
				{if $errorField == 'name'}
					<small class="innerError">
						{if $errorType == 'tooShort'}
							Der Repository-Name ist zu kurz. Er muss mindestens 2 Zeichen lang sein.
						{else if $errorType == 'noNumberOnStart'}
							Der Repository-Name darf nicht mit einer Zahl beginnen.
						{else if $errorType == 'wrongFormat'}
							Der Repository-Name darf lediglich folgende Zeichen enthalten: a-z, 0-9
						{else if $errorType == 'nameTooLong'}
							Der Repository-Name ist zu lang. Er darf maximal 20 Zeichen lang sein.
						{else if $errorType == 'alreadyUsed'}
							Es gibt bereits ein Repository mit diesem Namen.
						{/if}
					</small>
				{/if}
				<small>Gib den Repository-Namen ein. Der Name darf lediglich folgende Zeichen enthalten: a-z, 0-9</small>
			</dd>
		</dl>
	</section>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}