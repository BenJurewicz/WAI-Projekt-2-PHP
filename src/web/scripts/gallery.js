function changePage(num) {
	let currentPage = window.location.pathname.split("/").pop();
	let params = new URLSearchParams(window.location.search);
	let pageStr = params.get("page");
	if (pageStr == null) {
		pageStr = "0";
	}
	pageNum = Math.max(0, parseInt(pageStr) + num);

	window.location.href = currentPage + "?page=" + pageNum;
}
