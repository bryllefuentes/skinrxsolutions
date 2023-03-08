<script context="module">
	import { get } from "svelte/store";
	import ListItem from "./listItem.svelte";
</script>

<script>
	export let options;
	// export let pageNumber;
	export let store;
	// export let numOfTotalPages = 4;

	let display = {
		pagination_show_previous_and_next_buttons: true,
		pagination_show_first_and_last_buttons: true,
	};

	let mode = "frontend";

	let innerWidth = window.innerWidth;

	let buttons = [-2, -1, 0, 1, 2];
	let labels = {
		first: "first",
		last: "last",
		next: "next",
		previous: "previous",
	};

	// let numOfTotalPages;
	// store.numOfTotalPages.subscribe((value) => {
	// 	numOfTotalPages = value;
	// });

	// store.currentPage.subscribe((value) => {
	// 	currentPage = value;
	// });

	const onChange = (callbackName) => {
		store.onPaginationChange(callbackName);
		return;

		let pageNumber;
		switch (callbackName) {
			case "previous":
				pageNumber = currentPage - 1;
				break;
			case "next":
				pageNumber = currentPage + 1;
				break;
			case "first":
				pageNumber = 0;
				break;
			case "last":
				pageNumber = numOfTotalPages - 1;
				break;

			default:
				pageNumber = callbackName.split("-")[1];
				pageNumber = parseInt(pageNumber);
				break;
		}

		console.log("callbackName: " + callbackName);
		console.log("pageNumber: " + pageNumber);
		console.log("currentPage: " + currentPage);

		if (0 > pageNumber || pageNumber > numOfTotalPages - 1) {
			return;
		}

		store.updateCurrentPage(pageNumber);

		// ActionsHandlerInstance.doAction({
		// 	type: "CHANGE_CURRENT_PAGE",
		// 	payload: {
		// 		currentPage: pageNumber,
		// 	},
		// });
	};

	const isMobileScreen = (innerWidth) => innerWidth <= 640;

	// let showFirstAndLastButtons =
	// 	display["pagination_show_first_and_last_buttons"];

	// let showPreviousAndNextButtons =
	// 	isMobileScreen(innerWidth) ||
	// 	display["pagination_show_previous_and_next_buttons"];

	const showFirstAndLastButtons = (innerWidth) => {
		let show = display["pagination_show_first_and_last_buttons"];

		if (mode == "editor") {
			show = true;
		}

		if (isMobileScreen(innerWidth)) {
			show = false;
		}

		return show;
	};
	const showPreviousAndNextButtons = (innerWidth) => {
		let show = true;
		show = display["pagination_show_previous_and_next_buttons"];

		if (mode == "editor") {
			show = true;
		}

		return show;
	};

	const showNumberButtons = (innerWidth) => {
		let show = true;

		// Show Numbers for mobile only if prev and next buttons are hidden
		if (isMobileScreen(innerWidth)) {
			show = !display["pagination_show_previous_and_next_buttons"];
		}

		if (mode == "editor") {
			show = true;
		}

		return show;
	};

	let currentPage;

	const unsubscribe1 = store.currentPage.subscribe((value) => {
		console.log("currentPage value: ");
		console.log(value);

		currentPage = value;
	});

	let numOfTotalPages;
	store.numOfTotalPages.subscribe((value) => {
		numOfTotalPages = value;
	});
</script>

<svelte:window bind:innerWidth />

{#if 1 < numOfTotalPages}
	<nav role="pagination" class="helpiefaq__qna-pagination pagination">
		<ul>
			{#if showFirstAndLastButtons(innerWidth)}
				<ListItem
					id="first"
					label={labels.first}
					readOnly={currentPage === 0}
					callback={onChange}
				/>
			{/if}

			{#if showPreviousAndNextButtons(innerWidth)}
				<ListItem
					id="previous"
					label={labels.previous}
					icon={isMobileScreen(innerWidth) ? "arrow-left-alt2" : ""}
					readOnly={currentPage === 0}
					callback={onChange}
				/>
			{/if}

			{#if showNumberButtons(innerWidth)}
				{#each buttons as button}
					{#if currentPage + button >= 0 && currentPage + button < numOfTotalPages}
						<!-- currentPage + button: {currentPage + button} -->
						<ListItem
							id="page-{currentPage + button}"
							label={currentPage + button + 1}
							active={currentPage === currentPage + button}
							callback={onChange}
						/>
					{/if}
				{/each}
			{/if}
			{#if showPreviousAndNextButtons(innerWidth)}
				<ListItem
					id="next"
					label={labels.next}
					icon={isMobileScreen(innerWidth) ? "arrow-right-alt2" : ""}
					readOnly={currentPage > numOfTotalPages - 1}
					callback={onChange}
				/>
			{/if}
			{#if showFirstAndLastButtons(innerWidth)}
				<ListItem
					id="last"
					label={labels.last}
					readOnly={currentPage >= numOfTotalPages}
					callback={onChange}
				/>
			{/if}
		</ul>
	</nav>
{/if}
