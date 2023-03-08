<script>
	export let answer = {};
</script>

<div class="helpiefaq__singleqna__answer row">
	<div class="col col__2">&nbsp;</div>
	<div class="col col__10">
		<p>
			<!-- <span class="answer-icon">A</span> -->
			{answer.content}
		</p>
		<p class="qna-answer-meta">
			Answered by {answer.answer_by ? answer.answer_by : "anonymous"}
			{answer.human_time_diff}
		</p>
	</div>

	<!-- <VoteButton
    voteFor="answer"
    questionId={question.id}
    answerCommentId={answer.comment_ID}
    votes={answer.votes}
/> -->
</div>
