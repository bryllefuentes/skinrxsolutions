<script context="module">
	import StoreInstance from "./storeInstances.js";
</script>

<script>
	export let options;
	export let voteFor = "question";
	export let questionId = 0;
	export let answerCommentId = 0;
	export let votes = {
		up: 0,
		down: 0,
	};

	export let currentUserVotes = {};

	console.log("currentUserVotes: ");
	console.log(currentUserVotes);

	let alreadyVoted = false;

	$: upVotes = votes.up;
	let downVotes = votes.down;

	function initVoteStatus() {
		alreadyVoted = false;
		if (currentUserVotes && currentUserVotes.vote) {
			alreadyVoted = true;
		}

		console.log("initVoteStatus alreadyVoted :");
		console.log(alreadyVoted);
	}

	initVoteStatus();

	function get_icon(iconType, direction) {
		let icons = {
			arrow1: "dashicons dashicons-arrow-" + direction + "-alt",
			arrow2: "dashicons dashicons-arrow-" + direction + "-alt2",
			arrow: "dashicons dashicons-arrow-" + direction + "",
			thumbs: "dashicons dashicons-thumbs-" + direction + "",
		};

		return icons[iconType];
	}

	function postVote(vote, actionType = "add") {
		console.log("vote: " + vote);
		console.log("alreadyVoted: " + alreadyVoted);
		var reqData = {
			action: "helpie_handle_vote",
			nonce: thisModule.nonce,
			vote: vote,
			action_type: actionType,
			vote_for: voteFor,
			question_id: questionId,
			answer_comment_id: answerCommentId,
			current_post_id: helpie_faq_object.current_post_id,
		};

		if (alreadyVoted) {
			reqData["action_type"] = "remove";
			alreadyVoted = false;
			upVotes--;
		} else {
			upVotes++;
			alreadyVoted = true;
		}

		console.log("alreadyVoted: " + alreadyVoted);
		console.log("reqData: ");
		console.log(reqData);
		StoreInstance.postVote(reqData);

		// if (vote == "up") {
		// 	upVotes++;
		// } else {
		// 	downVotes++;
		// }
	}

	// $: additionalClasses = "";

	// if (alreadyVoted) {
	// 	additionalClasses += "alreadyVoted";
	// }
</script>

<div class="helpiefaq__vote__container">
	<div class="helpiefaq__votebutton__group">
		<button
			on:click={() => postVote("up")}
			class="single up blue {alreadyVoted ? 'alreadyVoted' : ''}"
		>
			<span class="icon {get_icon('arrow', 'up')} " />
			<span class="vote_count">{upVotes}</span>
		</button>

		{#if options["showDownVote"] == true}
			<button on:click={() => postVote("down")} class="single down">
				<span class="icon {get_icon('arrow', 'down')} " />
				<span class="vote_count">{downVotes}</span>
			</button>
		{/if}
	</div>
</div>
