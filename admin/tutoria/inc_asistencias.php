<?php if (! defined('INC_TUTORIA')) die ('<h1>Forbidden</h1>'); ?>

<!-- FALTAS DE ASISTENCIA -->

<h3>Faltas sin justificar</h3>

<?php $exp_inicio_curso = explode('-', $inicio_curso); ?>
<?php $inicio_curso2 = $exp_inicio_curso[2].'-'.$exp_inicio_curso[1].'-'.$exp_inicio_curso[0]; ?>

<?php $result = mysql_query("CREATE TABLE FALTASTEMP SELECT DISTINCT FALTAS.claveal, FALTAS.falta, COUNT(*) AS NUMERO, apellidos, nombre FROM FALTAS, FALUMNOS  
 WHERE FALTAS.claveal = FALUMNOS.claveal AND FALTAS.falta = 'F' AND FALTAS.unidad = '".$_SESSION['mod_tutoria']['unidad']."' GROUP BY FALTAS.claveal"); ?>

<?php $result = mysql_query("SELECT FALTASTEMP.claveal, FALTASTEMP.apellidos, FALTASTEMP.nombre, FALTASTEMP.NUMERO FROM FALTASTEMP ORDER BY FALTASTEMP.numero DESC"); ?>
<?php if (mysql_num_rows($result)): ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th>Alumno/a</th>
			<th class="text-center">Total</th>
		</tr>
	</thead>
	<tbody>
		<?php while ($row = mysql_fetch_array($result)): ?>
		<tr>
			<td><a href="../faltas/informes.php?claveal=<?php echo $row['claveal']; ?>&fecha4=<?php echo $inicio_curso2; ?>&fecha3=<?php echo date('d-m-Y'); ?>&submit2=2"><?php echo $row['nombre'].' '.$row['apellidos']; ?></a></td>
			<td class="text-center"><div class="badge"><?php echo $row['NUMERO']; ?></div></td>
		</tr>
		<?php endwhile; ?>
		<?php mysql_free_result($result); ?>
	</tbody>
</table>

<?php else: ?>

<br>
<p class="lead text-muted">No hay faltas de asistencias registradas para esta unidad.</p>
<br>

<?php endif; ?>

<?php mysql_query("DROP TABLE FALTASTEMP"); ?>

<!-- FIN FALTAS DE ASISTENCIA -->