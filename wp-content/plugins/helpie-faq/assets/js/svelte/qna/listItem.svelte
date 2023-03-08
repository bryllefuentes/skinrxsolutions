<script>
	export let id;
	export let label = "";
	export let icon = "";
	export let active = false;
	export let readOnly = false;
	export let callback = "";
</script>

<li class="tablesome-pagination__{id} pagination-{id}">
	<a disabled={readOnly} class:active on:click={() => callback(id)}>
		{#if id == "previous" && icon}
			<span class="dashicons dashicons-{icon}" />
		{/if}
		{label}
		{#if id == "next" && icon}
			<span class="dashicons dashicons-{icon}" />
		{/if}
	</a>
</li>
