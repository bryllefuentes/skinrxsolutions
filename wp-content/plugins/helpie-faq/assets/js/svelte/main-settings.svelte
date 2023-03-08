<script context="module">
	import { get } from "svelte/store";
</script>

<script>
	export let config;
	export let store;
	// export let executeRepeater;

	console.log("store: ");
	console.log(get(store.fields));

	const whatAmI = (me) => {
		return Object.prototype.toString.call(me).split(/\W/)[2];
	};

	const getDependency = (storeData, field) => {
		let showField = true;
		if (whatAmI(field) === "Object" && "dependency" in field) {
			field["dependency"].forEach((dependency) => {
				if (storeData[dependency["id"]] != dependency["value"]) {
					showField = false;
				}
			});
		}
		// console.log("getDependency: ");
		// console.log(storeData);
		// console.log(field);
		// console.log(
		// 	"field: " + Object.keys(field),
		// 	", showField: " + showField
		// );
		return showField;
	};

	// Called onRepeaterClick
	// const onPostGroupRepeaterClick = (config, store) => {
	// 	let fieldsToDuplicate = config.layout[2];

	// 	let args = [
	// 		'layout' => ['after', '']
	// 	]
	// 	this.executeRepeater(config, store, fieldsToDuplicate);

	// }
	// const executeRepeater = (config, store) => {
	// 	// Step 1: Get variables needed

	// 	// let layoutObjectIdToDuplicate = config.layout[2];
	// 	let fieldsToDuplicate = config.layout[2];
	// 	// let fieldsContainer = "";
	// 	let currentCounterValue = 2;
	// 	let nextCounterValue = currentCounterValue++;
	// 	let newFieldKeys = [];

	// 	// Step 2: Update Store Fields Object
	// 	let newFields = [];
	// 	fieldsToDuplicate.forEach(($key) => (field) => {
	// 		let oldKey = $key;
	// 		let newKey = oldKey.replace(/[0-9]/g, nextCounterValue);
	// 		store.addField(newKey, field);
	// 		newFieldKeys.push(newKey);
	// 	});

	// 	// Step 3: Update layout Object in Store
	// 	store.addLayoutRow(newFieldKeys);

	// 	// Step 4: Update Counter Hidden Field
	// 	store.updateFieldValue("post_group_repeater_count", nextCounterValue);
	// };

	let fieldsData = get(store.fields);

	console.log("config: ");
	console.log(config);
	config.layout.forEach((row) => {
		console.log("row");
		console.log(row);
	});
</script>

<div class="settings-container">
	{#each config.layout as row, key}
		<div class="row">
			{#each row.fields as field}
				{#if getDependency(fieldsData, config.fields[field])}
					<div class="column">
						<h3>{config.fields[field]["label"]}</h3>
					</div>
				{:else}
					<div class="column">Hidden</div>
				{/if}
			{/each}
		</div>
	{/each}
</div>
