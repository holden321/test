<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="libs/bootstrap.min.css" rel="stylesheet">
	<link href="libs/dataTables.bootstrap.min.css"
		  rel="stylesheet">
	<link href="style.css" rel="stylesheet">
	<script src="libs/jquery.min.js"></script>
	<script src="libs/bootstrap.min.js"></script>
	<script src="libs/jquery.dataTables.min.js"></script>
	<script src="libs/dataTables.bootstrap.min.js"></script>
	<script src="libs/ejs.min.js"></script>
	<script src="script.js"></script>
	<script>
		var membersTemplate = <?= json_encode(file_get_contents('members.ejs')) ?>;
	</script>
</head>
<body>

</body>
</html>
<div class=container>

	<h1 class="text-center">Олимпиада</h1>

	<form id="add_users">
		<div class="form-group">
			<label for="members">Участники</label>
			<input class="form-control" id="members" placeholder="введите имена участников через запятую">
			<!--small id="emailHelp" class="form-text text-muted">только кириллические буквы</small-->
		</div>
		<button type="submit" class="btn btn-primary">Добавить</button>
	</form>

	<div class="members"></div>


</div>

<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modalLabel">Ошибка</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

</div>