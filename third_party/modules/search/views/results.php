<table>
<?php
$i = 1;
foreach($results as $result):
?>
	<tr>
		<td><?php echo $i . '.'; ?></td>
		<td>
			<?php echo anchor($result->search_link, $result->search_title); ?><br />
			<?php echo $result->search_desc; ?><br />
		</td>
	</tr>

<?php
	$i++;
endforeach;
?>
</table>