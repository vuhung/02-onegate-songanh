<tr id='tr_cxl' >
	<td colspan="5">
		<table width="100%">
			<tr>
				<td valign="top" nowrap="nowrap"><font color="Blue">Người bút phê</font></td>
				<td>
				<?php 
					$userbp = WFEngine::GetAccessUserFromTransitionNoGroup($this->wf_id_t);
					$xltrans = WFEngine::GetNextTransitionsByTransition($this->wf_id_t);
					
				?>
					<select name=ID_U_BP>
						<?php foreach($userbp as $itemubp){?>
						<option value='<?=$itemubp["ID_U"]?>'><?=$itemubp["NAME"]?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top" nowrap="nowrap"><font color="Blue">Nội dung bút phê</font></td>
				<td width="100%">
					<textarea rows="2" style="width:99%" name=NOIDUNG_BP></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td width="295" valign="top">
								<?php echo QLVBDHCommon::writeMultiSelectDepartmentUser('DEP_NGUOIGUI','NGUOIGUI')?>
								<span style="margin-left:95px"><input type=button onclick="InsertIntoArr()" value="Chọn"></span>
							</td>
							<td width="100%" valign="top"><div id="listuser"></div></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
