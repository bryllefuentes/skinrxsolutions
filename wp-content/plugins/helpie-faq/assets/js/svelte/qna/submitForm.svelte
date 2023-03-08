<script context="module">
	import StoreInstance from "./storeInstances.js";
</script>

<script>
	export let type = "question";
	export let questionId = 0;

	$: showForm = false;
	let value = "";
	let submissionStatus = "";
	$: submissionMessage = "";

	console.log(
		"helpie_faq_object.current_post_id: " +
			helpie_faq_object.current_post_id
	);

	function updateForm(JsonResponse) {
		let response = JSON.parse(JsonResponse);
		console.log("submitForm response: ");
		console.log(response);

		submissionStatus = response.status;

		if (response.status == "published" || response.status == "awaiting") {
			showForm = false;
		}

		value = "";

		submissionMessage = "<p class='message'>" + response.message + "</p>";
		console.log("submissionMessage: " + submissionMessage);
	}

	async function postItem() {
		let loadingImageUrl =
			helpie_faq_object.url + "assets/img/moving-train.gif";
		// submissionMessage = "Submitting... Do not refresh.";
		submissionMessage =
			"<img class='gif' src='" + loadingImageUrl + "' alt='Loading'/>";
		let postargs = {
			action: "helpie_qna_add_post",
			nonce: thisModule.nonce,
			current_post_id: helpie_faq_object.current_post_id,
			type: type,
			question_id: questionId,
			value: value,
		};
		console.log("postargs: ");
		console.log(postargs);
		let response = await StoreInstance.postItem(postargs);

		await updateForm(response);
	}

	function onAddSubmissionClick() {
		showForm = true;
	}

	function cancelQna() {
		showForm = false;
	}
</script>

{@html submissionMessage}
<!-- <p class="message">{submissionMessage}</p> -->
{#if showForm == true}
	<textarea
		class="submission__box"
		bind:value
		placeholder="Add your {type} here"
	/>
	<button class="submit-qna {type}" on:click={postItem}>Submit {type}</button>
	<button class="cancel-qna" on:click={cancelQna}>Cancel</button>
{:else}
	<button class="submit-qna {type}" on:click={onAddSubmissionClick}
		>Add {type}</button
	>
{/if}

<style>
	.message {
		color: #157dec;
		font-size: 13px;
		font-style: italic;
	}

	.no_questions,
	.gif {
		padding-bottom: 15px;
	}
</style>
