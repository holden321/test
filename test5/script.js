$(function () {

	// load init data
	loadData({action: "load"}, function (result) {
		renderMembers(result.result);
	})

	$("#add_users").submit(function () {
		let members = $("#members").val().trim();
		$("input, button").prop("disabled", true);
		loadData({action: "add_members", members: members}, function (result) {
			renderMembers(result.result.members);
			$("#members").val("")
		}, () => {
		}, function () {
			$("input, button").prop("disabled", false);
		});
		return false;
	});

	function loadData(data, done, fail, always) {

		$.post("", data, 'json').done(_done).fail(_fail).always(_always);

		function _done(result) {
			if (result.error) {
				_fail(result.error);
			} else {
				done && done(result);
			}
		}

		function _fail(result) {
			$("#popup .modal-body").html(result);
			$('#popup').modal();
			fail && fail();
		}

		function _always() {
			always && always();
		}
	}

	function renderMembers(members) {

		let tableSelector = ".members table";

		let order = $(tableSelector).DataTable().order();

		$(".members").html(ejs.render(membersTemplate, {members: members}))

		let table = $(tableSelector).DataTable({
			paging: false,
			searching: false,
			info: false,
		});

		if (order) {
			table.order(order);
			table.draw();
		}
	}


});