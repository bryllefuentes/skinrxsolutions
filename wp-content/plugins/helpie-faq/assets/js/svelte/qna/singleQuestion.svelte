<script context="module">
	import VoteButton from "./voteButton.svelte";
	import SubmitForm from "./submitForm.svelte";
	import SingleAnswer from "./singleAnswer.svelte";
	import ShowMore from "./showMore.svelte";
</script>

<script>
	export let question;
	export let options;

	console.log("singleQuestion.svelte question: ");
	console.log(question);

	$: votes = question.votes;
	$: currentUserVotes = question.currentUserVotes;

	$: showMore = false;
	let boxOpen = false;

	function toggleShow(showCondition) {
		showMore = showCondition;
		console.log("showMore: " + showMore);
	}
</script>

<div class="helpiefaq__singleqna">
	<div class="helpiefaq__singleqna__question row">
		<!-- <div class='col__3'>{question.id}</div> -->
		<div class="col__2">
			{#if options["canVoteQuestion"]}
				<VoteButton
					voteFor="question"
					questionId={question.id}
					{votes}
					{currentUserVotes}
					{options}
				/>
			{/if}
			&nbsp;
		</div>
		<div class="col__10">
			<a href={question.url} target="_blank">
				<!-- <b>Q: </b> -->
				{question.content}</a
			>
		</div>
	</div>
	<!-- {question.id} -->
	{#each question.answers as answer, ii}
		{#if ii == 0 || showMore == true}
			<SingleAnswer {answer} />
		{/if}

		{#if question.answers.length > 1}
			<ShowMore {showMore} {ii} {toggleShow} />
		{/if}
	{/each}
	<div class="row">
		<div class="col col__2">&nbsp;</div>
		<div class="col col__10">
			{#if options["canAddAnswer"] && !question.userHasAlreadyAnswered}
				<SubmitForm type="answer" questionId={question.id} />
			{/if}
		</div>
	</div>
</div>
