<?php
$alerta = isset($alerta) && !empty($alerta) ? $alerta : ($this->session->flashdata('alerta') ? $this->session->flashdata('alerta') : null);
if($alerta){
	if($this->session->flashdata('alerta')){
		$alerta = $alerta["alerta"];
	}
	?>
	<div class="alert alert-dismissible <?php echo isset($alerta["type"]) && !empty($alerta["type"]) ? 'alert-' . $alerta["type"] : 'alert-danger'; ?> <?php echo isset($alerta["class"]) && !empty($alerta["class"]) ? (is_array($alerta["class"]) ? implode(" ", $alerta["class"]) : $alerta["class"]) : null; ?> <?php echo isset($alerta["status"]) && $alerta["status"] == 'visible' ? '' : 'display-hide'; ?>">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span>
			<?php
			if(isset($alerta["title"]) && !empty($alerta["title"])){
				?>
				<strong><?php echo $alerta["title"]; ?></strong><br />
				<?php
			}
			?>
			<?php echo isset($alerta["message"]) && !empty($alerta["message"]) ? $alerta["message"] : $this->lang->line('acesso_login_erro'); ?>
		</span>
	</div>
	<?php
}
?>