<?php
if (! defined('MOD_CALENDARIO')) die ('<h3>FORBIDDEN</h3>');

// CALENDARIOS PRIVADOS DEL PROFESOR
$result_calendarios1 = mysqli_query($db_con, "SELECT id, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0");
while ($calendario1 = mysqli_fetch_assoc($result_calendarios1)) {
	
	$result_eventos1 = mysqli_query($db_con, "SELECT * FROM calendario WHERE categoria='".$calendario1['id']."' AND YEAR(fechaini)=$anio AND MONTH(fechaini)=$mes");
	
	while ($eventos1 = mysqli_fetch_assoc($result_eventos1)) {
		
		$exp_fechaini_evento = explode('-', $eventos1['fechaini']);
		$fechaini_evento = $exp_fechaini_evento[2].'/'.$exp_fechaini_evento[1].'/'.$exp_fechaini_evento[0];
		
		$exp_fechafin_evento = explode('-', $eventos1['fechafin']);
		$fechafin_evento = $exp_fechafin_evento[2].'/'.$exp_fechafin_evento[1].'/'.$exp_fechafin_evento[0];
		
		echo '<form id="formEditarEvento" method="post" action="post/editarEvento.php?mes='.$mes.'&anio='.$anio.'">
			<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
			      </div>
			      <div class="modal-body">
		        
		        	<fieldset>
		        		
		        		<input type="hidden" name="cmp_evento_id" value="'.$eventos1['id'].'">
		        		
		        		<div class="form-group">
		        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
		        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" value="'.$eventos1['nombre'].'" autofocus>
		        		</div>
		        		
		        		
		        		<div class="row">
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker1">
		        					<label for="cmp_fecha_ini">Fecha inicio</label>
		        					<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="'.$fechaini_evento.'" data-date-format="DD/MM/YYYY">
		            					<span class="input-group-addon"><span class="fa fa-calendar">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker2">
		            				<label for="cmp_hora_ini">Hora inicio</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_hora_ini" name="cmp_hora_ini" value="'.substr($eventos1['horaini'], 0, -3).'" data-date-format="HH:mm">
		            					<span class="input-group-addon"><span class="fa fa-clock-o">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker3">
		            				<label for="cmp_fecha_fin">Fecha fin</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="'.$fechafin_evento.'" data-date-format="DD/MM/YYYY">
		            					<span class="input-group-addon"><span class="fa fa-calendar">
		            				</div>
		            			</div>
		        			</div>
		        			<div class="col-xs-6 col-sm-3">
		        				<div class="form-group datetimepicker4">
		            				<label for="cmp_hora_fin">Hora fin</label>
		            				<div class="input-group">
		            					<input type="text" class="form-control" id="cmp_hora_fin" name="cmp_hora_fin" value="'.substr($eventos1['horafin'], 0, -3).'" data-date-format="HH:mm">
		            					<span class="input-group-addon"><span class="fa fa-clock-o">
		            				</div>
		            			</div>
		        			</div>
		        		</div>
		        		
		        		<div class="form-group">
		        			<label for="cmp_descripcion">Descripci�n</label>
		        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion">'.$eventos1['descripcion'].'</textarea>
		        		</div>
		        		
		        		<div class="form-group">
		        			<label for="cmp_lugar">Lugar</label>
		        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar" value="'.$eventos1['lugar'].'">
		        		</div>
		        		
		        		<div class="form-group">
		        			<label for="cmp_calendario">Calendario</label>
		        			<select class="form-control" id="cmp_calendario" name="cmp_calendario" required>
		        				<optgroup label="Mis calendarios">';
		        					$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0");
		        					while ($row = mysqli_fetch_assoc($result)):
		        					echo '<option value="'.$row['id'].'"';
		        					if ($eventos1['categoria'] == $row['id']) echo ' selected';
		        					echo '>'.$row['nombre'].'</option>';
		        					endwhile;
		        					mysqli_free_result($result);
		        				echo '</optgroup>';
		        				if (stristr($_SESSION['cargo'],'1') || stristr($_SESSION['cargo'],'4') || stristr($_SESSION['cargo'],'5')):
		        				echo '<optgroup label="Otros calendarios">';
		        				$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1 $sql_where");
		        				while ($row = mysqli_fetch_assoc($result)):
		        					echo '<option value="'.$row['id'].'"';
		        					if ($eventos1['categoria'] == $row['id']) echo ' selected';
		        					echo '>'.$row['nombre'].'</option>';
		        				endwhile;
		        				mysqli_free_result($result);
		        				echo '</optgroup>';
		        				endif;
		        			echo '</select>
		        		</div>
		        		
		        		
		        		<div id="opciones_diario">';
		        		
		        		$result = mysqli_query($db_con, "SELECT DISTINCT grupo, materia FROM profesores WHERE profesor='".$_SESSION['profi']."'");
		        		if (mysqli_num_rows($result)):
		        		
		        		if ($eventos1['unidades'] != "" && $eventos1['asignaturas'] != "") {
		        			$eventos1['unidades'] = str_replace('; ', ';', $eventos1['unidades']);
		        			$eventos1['asignaturas'] = str_replace('; ', ';', $eventos1['asignaturas']);
		        			
		        			$exp_unidades = explode(';', $eventos1['unidades']);
		        			$exp_asignaturas = explode(';', $eventos1['asignaturas']);
		        		}
		        		
		        		echo '<div class="form-group">
		        				<label for="cmp_unidad_asignatura">Unidad y asignatura</label>
		        				
		        				<select class="form-control" id="cmp_unidad_asignatura" name="cmp_unidad_asignatura[]" size="5" multiple>';
		        			
		        			$i = 0;
		        			
		        			while ($row = mysqli_fetch_array($result)):
		        					echo '<option value="'.$row['grupo'].' => '.$row['materia'].'"';
		        					if (in_array($row['grupo'], $exp_unidades) && in_array($row['materia'], $exp_asignaturas)) echo ' selected';
		        					echo '>'.$row['grupo'].' ('.$row['materia'].')'.'</option>';
		        					$i++;
		        			endwhile;
		        			echo'</select>
		        			</div>';
		        		endif;
		        		echo '</div>
		        						        				        		
		        	</fieldset>
		        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-danger" formaction="post/eliminarEvento.php?mes='.$mes.'&anio='.$anio.'">Eliminar</button>
			        <button type="submit" class="btn btn-primary">Modificar</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</form>';	
	}
	mysqli_free_result($result_eventos1);
}
mysqli_free_result($result_calendarios1);

// CALENDARIOS PUBLICOS
$result_calendarios1 = mysqli_query($db_con, "SELECT id, color FROM calendario_categorias WHERE espublico=1");
while ($calendario1 = mysqli_fetch_assoc($result_calendarios1)) {
	
	$result_eventos1 = mysqli_query($db_con, "SELECT * FROM calendario WHERE categoria='".$calendario1['id']."' AND YEAR(fechaini)=$anio AND MONTH(fechaini)=$mes");
	
	while ($eventos1 = mysqli_fetch_assoc($result_eventos1)) {
	
		$exp_fechaini_evento = explode('-', $eventos1['fechaini']);
		$fechaini_evento = $exp_fechaini_evento[2].'/'.$exp_fechaini_evento[1].'/'.$exp_fechaini_evento[0];
		
		$exp_fechafin_evento = explode('-', $eventos1['fechafin']);
		$fechafin_evento = $exp_fechafin_evento[2].'/'.$exp_fechafin_evento[1].'/'.$exp_fechafin_evento[0];
		
		if (stristr($_SESSION['cargo'],'1')) {
			echo '<form id="formEditarEvento" method="post" action="post/editarEvento.php?mes='.$mes.'&anio='.$anio.'">
				<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
				  <div class="modal-dialog modal-lg">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
				      </div>
				      <div class="modal-body">
			        
			        	<fieldset>
			        		
			        		<input type="hidden" name="cmp_evento_id" value="'.$eventos1['id'].'">
			        		
			        		<div class="form-group">
			        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
			        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" value="'.$eventos1['nombre'].'" autofocus>
			        		</div>
			        		
			        		
			        		<div class="row">
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker1">
			        					<label for="cmp_fecha_ini">Fecha inicio</label>
			        					<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="'.$fechaini_evento.'" data-date-format="DD/MM/YYYY">
			            					<span class="input-group-addon"><span class="fa fa-calendar">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker2">
			            				<label for="cmp_hora_ini">Hora inicio</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_hora_ini" name="cmp_hora_ini" value="'.substr($eventos1['horaini'], 0, -3).'" data-date-format="HH:mm">
			            					<span class="input-group-addon"><span class="fa fa-clock-o">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker3">
			            				<label for="cmp_fecha_fin">Fecha fin</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="'.$fechafin_evento.'" data-date-format="DD/MM/YYYY">
			            					<span class="input-group-addon"><span class="fa fa-calendar">
			            				</div>
			            			</div>
			        			</div>
			        			<div class="col-xs-6 col-sm-3">
			        				<div class="form-group datetimepicker4">
			            				<label for="cmp_hora_fin">Hora fin</label>
			            				<div class="input-group">
			            					<input type="text" class="form-control" id="cmp_hora_fin" name="cmp_hora_fin" value="'.substr($eventos1['horafin'], 0, -3).'" data-date-format="HH:mm">
			            					<span class="input-group-addon"><span class="fa fa-clock-o">
			            				</div>
			            			</div>
			        			</div>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_descripcion">Descripci�n</label>
			        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion">'.$eventos1['descripcion'].'</textarea>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_lugar">Lugar</label>
			        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar" value="'.$eventos1['lugar'].'">
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_calendario">Calendario</label>
			        			<select class="form-control" id="cmp_calendario" name="cmp_calendario" required>
			        				<optgroup label="Mis calendarios">';
			        					$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['ide']."' AND espublico=0");
			        					while ($row = mysqli_fetch_assoc($result)):
			        					echo '<option value="'.$row['id'].'"';
			        					if ($eventos1['categoria'] == $row['id']) echo ' selected';
			        					echo '>'.$row['nombre'].'</option>';
			        					endwhile;
			        					mysqli_free_result($result);
			        				echo '</optgroup>';
			        				if (stristr($_SESSION['cargo'],'1') || stristr($_SESSION['cargo'],'4') || stristr($_SESSION['cargo'],'5')):
			        				echo '<optgroup label="Otros calendarios">';
			        				$result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1 $sql_where");
			        				while ($row = mysqli_fetch_assoc($result)):
			        					echo '<option value="'.$row['id'].'"';
			        					if ($eventos1['categoria'] == $row['id']) echo ' selected';
			        					echo '>'.$row['nombre'].'</option>';
			        				endwhile;
			        				mysqli_free_result($result);
			        				echo '</optgroup>';
			        				endif;
			        			echo '</select>
			        		</div>';
			        		
			        		if (stristr($_SESSION['cargo'],'1') || stristr($_SESSION['cargo'],'4') || stristr($_SESSION['cargo'],'5')):
			        		echo '<div id="opciones_actividades" class="row">
			        			
			        			<div class="col-sm-6">
			        		
			        				<div class="form-group">
			        					<label for="cmp_departamento">Departamento que lo organiza</label>
			        					<select class="form-control" id="cmp_departamento" name="cmp_departamento">';
			        						if (!(stristr($_SESSION['cargo'],'1') == TRUE) and !(stristr($_SESSION['cargo'],'5') == TRUE) and !(stristr($_SESSION['cargo'],'d') == TRUE)):
			        						$result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento='".$_SESSION['dpt']."' ORDER BY departamento ASC");
			        						while ($row = mysqli_fetch_assoc($result)):
			        						echo '<option value="'.$row['departamento'].'"';
			        						if ($eventos1['departamento'] == $row['departamento']) echo ' selected';
			        						echo '>'.$row['departamento'].'</option>';
			        						endwhile;
			        						elseif (stristr($_SESSION['cargo'],'d') == TRUE):
			        						echo '<option value="Relaciones de G�nero"';
			        						if ("Relaciones de G�nero" == $row['departamento']) echo ' selected';
			        						echo '>Relaciones de G�nero</option>';
			        						else:
			        						echo '<option value="M�ltiples Departamentos"';
			        						if ("M�ltiples Departamentos" == $row['departamento']) echo ' selected';
			        						echo '>M�ltiples Departamentos</option>
			        						<option value="Actividades Extraescolares"';
			        						if ("Actividades Extraescolares" == $row['departamento']) echo ' selected';
			        						echo '>Actividades Extraescolares</option>
			        						<option value="Relaciones de G�nero"';
			        						if ("Relaciones de G�nero" == $row['departamento']) echo ' selected';
			        						echo '>Relaciones de G�nero</option>';
			        						$result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Conserjeria' AND departamento <> 'Administracion' ORDER BY departamento ASC");
			        						while ($row = mysqli_fetch_assoc($result)):
			        						echo '<option value="'.$row['departamento'].'"';
			        						if ($eventos1['departamento'] == $row['departamento']) echo ' selected';
			        						echo '>'.$row['departamento'].'</option>';
			        						endwhile;
			        						endif;
			        		echo'			</select>
			        				</div>
			        				
			        				<div class="form-group">
			        					<label for="cmp_profesores">Profesores que asistir�n a la actividad</label>
			        					<select class="form-control" id="cmp_profesores" name="cmp_profesores[]" size="21" multiple>';
			        						$result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Conserjeria' AND departamento <> 'Administracion' ORDER BY departamento ASC");
			        						while ($row = mysqli_fetch_assoc($result)):
			        						$result_depto = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos WHERE departamento = '".$row['departamento']."' ORDER BY nombre ASC");
			        						echo '<optgroup label="'.$row['departamento'].'">';
			        							while ($row_profe = mysqli_fetch_assoc($result_depto)):
			        							echo '<option value="'.$row_profe['nombre'].'"';
			        							$exp_profesores = explode (';',str_replace('; ',';',$eventos1['profesores']));
			        							if (in_array($row_profe['nombre'], $exp_profesores)) echo ' selected';
			        							echo '>'.$row_profe['nombre'].'</option>';
			        							endwhile;
			        						echo '</optgroup>';
			        						endwhile;
			        					echo '</select>
			        					<p class="help-block">Para seleccionar varios profesores, mant�n apretada la tecla <kbd>Ctrl</kbd> mientras los vas marcando con el rat�n.</p>
			        				</div>
			        				
			        			</div><!-- /.col-sm-6 -->
			        			
			        			<div class="col-sm-6">
			        				
			        				<div class="form-group">
			        					<label for="">Unidades que asistir�n a la actividad</label>';
			        		    		$result = mysqli_query($db_con, "SELECT DISTINCT curso FROM alma ORDER BY curso ASC");
			        		    		while($row = mysqli_fetch_assoc($result)):
			        		    			echo '<p class="text-info">'.$row['curso'].'</p>';
			        		    			$result1 = mysqli_query($db_con, "SELECT DISTINCT unidad FROM alma WHERE curso = '".$row['curso']."' ORDER BY unidad ASC");
			        		    			while($row1 = mysqli_fetch_array($result1)):
			        		    		                 
			        		    			echo '<div class="checkbox-inline"> 
			        		    				<label>
			        		    					<input name="cmp_unidades[]" type="checkbox" value="'.$row1['unidad'].'"';
			        		    					$exp_unidades = explode (';',str_replace('; ',';',$eventos1['unidades']));
			        		    					if (in_array($row1['unidad'], $exp_unidades)) echo ' checked';
			        		    					echo '>'.$row1['unidad'].'
			        		    		        </label>
			        		    		    </div>';
			        		    		    
			        		    		endwhile;
			        		    		endwhile;
			        		    	echo'</div>
			        				
			        			</div><!-- /.col-sm-6 -->
			        		</div><!-- /.row -->';
			        		endif;
			        		
			        						        				        		
			        	echo '</fieldset>
			        
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				        <button type="submit" class="btn btn-danger" formaction="post/eliminarEvento.php?mes='.$mes.'&anio='.$anio.'">Eliminar</button>
				        <button type="submit" class="btn btn-primary">Modificar</button>
				      </div>
				    </div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</form>';
		}
		else {
			$exp_fechaini_evento = explode('-', $eventos1['fechaini']);
			$fechaini_evento = $exp_fechaini_evento[2].'/'.$exp_fechaini_evento[1].'/'.$exp_fechaini_evento[0];
			
			$exp_fechafin_evento = explode('-', $eventos1['fechafin']);
			$fechafin_evento = $exp_fechafin_evento[2].'/'.$exp_fechafin_evento[1].'/'.$exp_fechafin_evento[0];
			
			echo '<div id="modalEvento'.$eventos1['id'].'" class="modal fade">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$eventos1['nombre'].'</h4>
			      </div>
			      <div class="modal-body">
	        		
	        		<div class="row">
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	        					<label for="">Fecha inicio</label>
	            				<p class="form-control-static text-info">'.$fechaini_evento.'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="">Hora inicio</label>
	            				<p class="form-control-static text-info">'.substr($eventos1['horaini'], 0, -3).'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="">Fecha fin</label>
	            				<p class="form-control-static text-info">'.$fechafin_evento.'</p>
	            			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group">
	            				<label for="">Hora fin</label>
	            				<p class="form-control-static text-info">'.substr($eventos1['horafin'], 0, -3).'</p>
	            			</div>
	        			</div>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="">Descripci�n</label>
	        			<p class="form-control-static text-info">'.$eventos1['descripcion'].'</p>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="">Lugar</label>
	        			<p class="form-control-static text-info lead">'.$eventos1['lugar'].'</p>
	        		</div>';
	        	if($eventos1['categoria'] == 2) {	
	        		echo'	<div class="form-group">
	        			<label for="">Departamento que lo organiza</label>
	        			<p class="form-control-static text-info">'.$eventos1['departamento'].'</p>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="">Profesores que asistir�n a la actividad</label>
	        			<p class="form-control-static text-info">'.str_replace(';', ' | ',$eventos1['profesores']).'</p>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="">Unidades que asistir�n a la actividad</label>
	        			<p class="form-control-static text-info">'.str_replace(';', ' | ',$eventos1['unidades']).'</p>
	        		</div>';
	        	}
			        
			   echo'   </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';	
		}
	}
	mysqli_free_result($result_eventos1);
}
mysqli_free_result($result_calendarios1);
?>