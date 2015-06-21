<?php
require('../../bootstrap.php');

if((!(stristr($_SESSION['cargo'],'1') == TRUE)) and (!(stristr($_SESSION['cargo'],'c') == TRUE)) )
{
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');
	exit;
}

$PLUGIN_DATATABLES = 1;
include("../../menu.php");
include("menu.php");
	
?>
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
	  <h2>Biblioteca <small>Gesti�n de los Pr�stamos</small></h1>
		<h3 class="text-info">Lista de morosos</small></h3>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
	<!-- COLUMNA CENTRAL -->
  <div class="col-sm-12">
<form name="form1" action="edicion.php" method="post">
<table class='table table-striped datatable'>
	<thead>
		<tr>		
		<th></th>
		<th>Grupo </th>
		<th>Alumno </th>
		<th>T�tulo </th>
		<th nowrap>Fecha dev.</th>
		<th></th>
		</tr>	
	</thead>
	<tbody>
	<?
	$fecha_act = date('Y-m-d');
	$lista=mysqli_query($db_con, "select curso, apellidos, nombre, ejemplar, devolucion, amonestacion, id, sms from morosos order by devolucion, apellidos asc");

	$n=0;
	while ($list=mysqli_fetch_array($lista)){
		
	?>
	<tr>
	<? 	
		$n+=1   
	?>
		<td style="text-align: center"><input type="checkbox" name="id[]"
			value="<? echo $list[6] ;?>" /></td>
		<td style="text-align: center"><? echo $list[0];   ?></td>
		<td><? echo $list[1].', '.$list[2];   ?></td>
		<td><? echo $list[3];   ?></td>
		<td nowrap style="text-align: center"><? echo $list[4];   ?></td>
		<td style="text-align: left" nowrap>
		<?
		if ($list[7] == "SI") {
			echo '<span class="fa fa-comment fa-fw fa-lg" data-bs="tooltip" title="Se ha enviado SMS de advertencia"></span>';
		}
		if ($list[5]=='SI') {
			echo '<span class="fa fa-bolt fa-fw fa-lg" data-bs="tooltip" title="Se ha regsitrado una amonestaci�n"></span>';
		}
		?>
		</td>

	</tr>
	<?	
	}
	?>
	</tbody>
</table>

<hr>
<button type="submit" class="btn btn-danger" name="borrar" value="Borrar"><span class="fa fa-trash-o fa-fw"></span> Borrar</button>
<button type="submit" class="btn btn-info" name="sms" value="sms"><span class="fa fa-mobile fa-fw"></span> Enviar SMS</button>
<button type="submit" class="btn btn-warning" name="registro" value="registro"><span class="fa fa-gavel fa-fw"></span> Registrar Amonestaciones</button>
<a href="lpdf.php" class="btn btn-primary" target="_blank">Listado en PDF</a>
</form>

<?php include("../../pie.php");?>
	<script>
	$(document).ready(function() {
	  var table = $('.datatable').DataTable({
	  	  "paging":   true,
	      "ordering": true,
	      "info":     false,
	      
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
	  		
	  		"order": [[ 1, "asc" ]],
	  		
	  		"language": {
	  		            "lengthMenu": "_MENU_",
	  		            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
	  		            "info": "P�gina _PAGE_ de _PAGES_",
	  		            "infoEmpty": "No hay resultados disponibles.",
	  		            "infoFiltered": "(filtrado de _MAX_ resultados)",
	  		            "search": "Buscar: ",
	  		            "paginate": {
	  		                  "first": "Primera",
	  		                  "next": "�ltima",
	  		                  "next": "",
	  		                  "previous": ""
	  		                }
	  		        }
	  	});
	});
	</script>
	<script>
	function selectall(form) {  
	 var formulario = eval(form)  
	 for (var i=0, len=formulario.elements.length; i<len ; i++)  
	  {  
	    if ( formulario.elements[i].type == "checkbox" )  
	      formulario.elements[i].checked = formulario.elements[0].checked  
	  }  
	}  
	</script>  

</body>
</html>
