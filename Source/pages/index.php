<?php

# Copyright (c) 2012 John Reese
# Licensed under the MIT license

access_ensure_global_level( plugin_config_get( 'view_threshold' ) );
$t_can_manage = access_has_global_level( plugin_config_get( 'manage_threshold' ) );

$t_show_stats = plugin_config_get( 'show_repo_stats' );
$t_class = $t_show_stats ? 'width75' : 'width60';
$t_title_span = $t_show_stats ? 2 : 1;
$t_links_span = $t_show_stats ? 4 : 2;

$t_repos = SourceRepo::load_all();

html_page_top1( plugin_lang_get( 'title' ) );
html_page_top2();
?>

<?php if ( $t_can_manage ) { ?>
<br/>
<form action="<?php echo plugin_page( 'repo_create' ) ?>" method="post">
<?php echo form_security_field( 'plugin_Source_repo_create' ) ?>
<table class="width50" align="center" cellspacing="1">

<tr>
<td class="form-title" colspan="2"><?php echo plugin_lang_get( 'create_repository' ) ?></td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
<td class="category"><?php echo plugin_lang_get( 'name' ) ?></td>
<td><input name="repo_name" maxlength="128" size="40"/></td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
<td class="category"><?php echo plugin_lang_get( 'type' ) ?></td>
<td>
<select name="repo_type">
	<option value=""><?php echo plugin_lang_get( 'select_one' ) ?></option>
<?php foreach( SourceTypes() as $t_type => $t_type_name ) { ?>
	<option value="<?php echo $t_type ?>"><?php echo string_display( $t_type_name ) ?></option>
<?php } ?>
</select>
</td>
</tr>

<tr>
<td class="center" colspan="2"><input type="submit" value="<?php echo plugin_lang_get( 'create_repository' ) ?>"/></td>
</tr>

</table>
</form>
<?php } ?>

<br/>
<table class="<?php echo $t_class ?>" align="center" cellspacing="1">

<tr>
<td class="form-title" colspan="<?php echo $t_title_span ?>"><?php echo plugin_lang_get( 'repositories' ) ?></td>
<td class="right" colspan="<?php echo $t_links_span ?>">
<?php
print_bracket_link( plugin_page( 'search_page' ), plugin_lang_get( 'search' ) );
if ( $t_can_manage ) { print_bracket_link( plugin_page( 'manage_config_page' ), plugin_lang_get( 'configuration' ) ); }
$t_index = 1;
?>
</td>
</tr>

<tr class="row-category">
<td width="5%">Index</td>
<td width="35%"><?php echo plugin_lang_get( 'repository' ) ?></td>
<?php if ( $t_show_stats ) { ?>
<td width="10%"><?php echo plugin_lang_get( 'changesets' ) ?></td>
<td width="10%"><?php echo plugin_lang_get( 'files' ) ?></td>
<td width="10%"><?php echo plugin_lang_get( 'issues' ) ?></td>
<?php } ?>
<td width="15%"><?php echo plugin_lang_get( 'type' ) ?></td>
</tr>

<?php foreach( $t_repos as $t_repo ) { ?>
<tr <?php echo helper_alternate_class() ?>>
<td><?php echo $t_index; $t_index = $t_index + 1; ?></td>
<td>
<table width="100%"><tr>
<td class="left">
<?php
	$t_stats = $t_repo->stats();
	if ( !$t_show_stats && $t_stats['changesets'] > 0 ) {
		print_link( plugin_page( 'list' ) . '&id=' . $t_repo->id, string_display( $t_repo->name ));
	} else {
		echo string_display( $t_repo->name );
	}
?>
</td>
<td class="right">
<?php 
	if ( $t_can_manage ) {
		if ( preg_match( '/^Import \d+-\d+\d+/', $t_repo->name ) ) {
			print_bracket_link( plugin_page( 'repo_delete' ) . '&id=' . $t_repo->id . form_security_param( 'plugin_Source_repo_delete' ), plugin_lang_get( 'delete' ) );
		}
		print_bracket_link( plugin_page( 'repo_manage_page' ) . '&id=' . $t_repo->id, plugin_lang_get( 'manage' ) );
	}
?>
</td>
</tr>
</table>
</td>
<?php if ( $t_show_stats ) { $t_stats = $t_repo->stats(); ?>
<td class="right"><?php
	if ( $t_stats['changesets'] > 0 ) {
		print_link( plugin_page( 'list' ) . '&id=' . $t_repo->id, $t_stats['changesets']);
	} else {
		echo $t_stats['changesets'];
	}
	?>
</td>
<td class="right"><?php echo $t_stats['files'] ?></td>
<td class="right"><?php echo $t_stats['bugs'] ?></td>
<?php } ?>
<td class="center"><?php echo string_display( SourceType( $t_repo->type ) ) ?></td>
</tr>
<?php } ?>

</table>

<?php
html_page_bottom1( __FILE__ );

