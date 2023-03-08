<script>
	export let showMore;
	export let ii;
	export let toggleShow;

	$: showMoreText = showMore ? "See Less Answers" : "See More Answers";
</script>

{#if ii == 0}
	<div class="row">
		<div class="col col__2">&nbsp;</div>
		<div class="col col__10">
			<div class="showMore">
				<a on:click={() => toggleShow(!showMore)}>{showMoreText}</a>
			</div>
		</div>
	</div>
{/if}

<style>
	.showMore a:hover {
		cursor: pointer;
	}
</style>
