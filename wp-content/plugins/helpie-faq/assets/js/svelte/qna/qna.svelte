<script context="module">
	import { onMount } from "svelte";
	import SubmitForm from "./submitForm.svelte";
	import SingleQuestion from "./singleQuestion.svelte";
	import Pagination from "./pagination.svelte";
	import StoreInstances from "./storeInstances.js";
	import { get } from "svelte/store";
</script>

<script>
	let qnaCapabilities = helpie_faq_object.qna_capabilities;
	let qnaStore = StoreInstances;
	let questions = [];
	let options = {
		showUpVote: true,
		showDownVote: false,
		canVoteQuestion: qnaCapabilities["can_vote_question"],
		canAddAnswer: qnaCapabilities["can_add_answer"],
		canAddQuestion: qnaCapabilities["can_add_question"],
		// 'canVoteAnswer': false,
	};
	let postargs = {
		action: "helpie_qna_get_posts",
		nonce: thisModule.nonce,
		current_post_id: helpie_faq_object.current_post_id,
	};

	let promise = getPosts(postargs);

	$: searchText = "";
	$: currentPage = 1;

	// console.log("qnaStore: ");
	// console.log(qnaStore);
	// console.log("questions: ");
	// console.log(questions);
	// console.log("qnaCapabilities: ");
	// console.log(qnaCapabilities);

	async function getPosts(postargs) {
		await qnaStore.getPosts(postargs);
	}

	// console.log("qnaStore.viewableQuestions.subscribe: ");
	// console.log(qnaStore.viewableQuestions.subscribe);

	const unsubscribe1 = qnaStore.viewableQuestions.subscribe((value) => {
		console.log("viewableQuestions value: ");
		questions = [];
		console.log(value);

		questions = JSON.parse(JSON.stringify(value));
	});

	const unsubscribe2 = qnaStore.searchText.subscribe((value) => {
		console.log("searchText value: " + value);
		searchText = value;
	});

	const unsubscribe3 = qnaStore.currentPage.subscribe((value) => {
		console.log("searchText value: " + value);
		currentPage = value;
	});

	let loadingImageUrl = helpie_faq_object.url + "assets/img/moving-train.gif";
</script>

<div class="helpiefaq__qna__section">
	<div />
	{#await promise}
		<!-- <p>...waiting</p> -->
		<img class="gif" src={loadingImageUrl} alt="Loading" />
	{:catch error}
		<p style="color: red">{error.message}</p>
	{/await}

	<input
		class="search"
		bind:value={searchText}
		type="text"
		placeholder="Search.."
		on:input={() => {
			qnaStore.setSearchText(searchText);
		}}
	/>
	{#if 0 < questions.length}
		{#each questions as question}
			<SingleQuestion {options} {question} {qnaStore} />
		{/each}
	{:else}
		<p class="no_questions">No Questions Found</p>
	{/if}
	<div class="row">
		<div class="col col__2">&nbsp;</div>
		<div class="col col__10">
			{#if options["canAddQuestion"]}
				<SubmitForm type="question" />
			{/if}
		</div>
	</div>
	<Pagination {options} store={qnaStore} {searchText} />
</div>

<style>
	.no_questions,
	.gif {
		padding-bottom: 15px;
	}
</style>
