<script context="module">
	import { onMount } from "svelte";
	import TailwindCSS from "./tailwindcss.svelte";
	import Select from "svelte-select";
	import Notice from "./notice.svelte";
	import { get } from "svelte/store";

	import MainSettings from "./main-settings.svelte";
	import Builder from "@essekia/layout-builder";
	import ActionsHandler from "./actions";
	import EventsHandler from "./eventsHandler";
	// import elementsConfig from "./config.json";
	import { getElementsOnlyWhenDependencyConditionMet } from "./reducer.js";
</script>

<script>
	// Start of New Script
	export let config;
	export let store;
	let actionsHandler = new ActionsHandler(store);
	let eventsHandler = new EventsHandler(store);

	console.log("store.fields: ");
	console.log(get(store.fields));
	console.log(store);

	// End of New Script
	// const isPremium = false;

	const isPremium = helpie_faq_object["faq_plan"] === "premium";
    const trialURL = helpie_faq_object["trial_url"];

	const integratedPostTypes = helpie_faq['faq_group']['post_types'];
	let inactivePluginsPostTypesLabels = [];
	for (const [postType, postTypeInfo] of Object.entries(integratedPostTypes)) {
  		if(postTypeInfo.active === false){
			inactivePluginsPostTypesLabels.push(postTypeInfo.label);
		}
	}
	
	onMount(async () => {});

	let sideNavStyle = "";
	let angle = "left";
	let sidebarToggleTitle = "Close";

	const toggleSideMenu = () => {
		if (angle == "left") {
			angle = "right";
			sideNavStyle = "min-width: 36px; width: 36px;";
			sidebarToggleTitle = "Open";
		} else {
			angle = "left";
			sideNavStyle = "min-width: 300px; width: 300px;";
			sidebarToggleTitle = "Close";
		}
	};
	

	let elementsData;
	const unsubscribeElements = store.elements.subscribe((value) => {
		// elementsData = JSON.parse(JSON.stringify(value));
		elementsData = jQuery.extend(true, {}, value );
			Object.keys(elementsData).forEach(key => {
			elementsData[key]['callback'] = eventsHandler.elementsCallback.bind(eventsHandler);	
		});

		// console.log("subscribe - elementsData: " + elementsData);
		// console.log(elementsData);
		elementsData = getElementsOnlyWhenDependencyConditionMet(elementsData);
		// console.log("after getElementsOnlyWhenDependencyConditionMet() - elementsData: ");
		// console.log(elementsData);		
	});

	let fields;
	store.fields.subscribe((value) => {
		console.log('Subscribing the Fields Value : ' , value);
		fields = value;
	})	

	let layoutData;

	// const unsubscribe = store.layout.subscribe((value) => {
	// 	layoutData = value;
	// });

	const get_layout_for_layout_builder = (layoutData) => {
		let layout = layoutData;

		let new_layout = [];
		let ii = 0;
		// foreach (layout as $key => $value) {
		//     $new_layout[$ii] = $value;
		//     $new_layout[$ii]['id'] = $key;
		//     $ii++;
		// }
		layout.forEach(function callback(value, key) {
			new_layout[ii] = value;
			new_layout[ii]["id"] = key;
			ii++;
		});

		console.log("get_layout_for_layout_builder - new_layout: ");
		console.log(new_layout);
		return new_layout;
	};


	console.log("elementsData: ");
	console.log(elementsData);
	$: bindFieldsObjectToInputElement(fields);

	function bindFieldsObjectToInputElement(fields){
		let settingsObject = {
			fields: fields,
			faq_group_location_index: store.faq_group_location_index
		};
		jQuery('input[name="faq_group_setting_fields_object"]').val(
            JSON.stringify(settingsObject)
        );
	}
	
</script>



<TailwindCSS />

<div style={sideNavStyle} class="helpiefaq__group helpiefaq__sidebarnav">
	<span class:helpiefaq__sidebarnav--overlay={angle == "right"} />
	<span
		on:click={toggleSideMenu}
		class="helpiefaq__sidebarnav--toggle dashicons dashicons-arrow-{angle}-alt"
		title={sidebarToggleTitle}
	/>
	<div class="helpiefaq__group__settings">
		<div>
            <div class="helpiefaq__title">
                
                {#if !isPremium}
                    <span class="helpiefaq__premiumTag">PRO</span>
                {/if}
            </div>
            {#if !isPremium}
                <Notice
                    type="warning"
                    message="This feature is only available in the premium plan..."
                    link={{
                        url: trialURL,
                        text: "Upgrade to PRO now",
                    }}
                />
            {/if}
        </div>

		<div>
			<div class="helpiefaq__title">Settings</div>
			<Notice
					type="info"
					message="The below styles would not be applied on the Products, LMS pages. Those style would come from Global Settings."
				/>

			{#if inactivePluginsPostTypesLabels && inactivePluginsPostTypesLabels.length > 0}
				<Notice
					type="info"
					message="Please activate the {inactivePluginsPostTypesLabels.join(', ')} plugin for this setting to work."
				/>
			{/if}
		</div>
		<Builder elements={elementsData} />
		<!-- <MainSettings {store} {config} /> -->
	</div>
</div>

<style>
	.helpiefaq__sidebarnav--overlay {
		@apply helpiefaq-top-0 helpiefaq-right-0 helpiefaq-left-0 helpiefaq-bottom-0 helpiefaq-bg-gray-300 helpiefaq-absolute helpiefaq-z-10 helpiefaq-h-full helpiefaq-flex;
	}

	.helpiefaq__sidebarnav--toggle {
		@apply helpiefaq-p-2 helpiefaq-absolute helpiefaq-top-1 helpiefaq-right-0 helpiefaq-rounded-l-lg helpiefaq-bg-gray-200 hover:helpiefaq-cursor-pointer helpiefaq-z-10;
	}

	.helpiefaq__sidebarnav {
		width: 300px;
		min-width: 300px;
		min-height: 700px;
		max-height: 1280px;
		background: #f0f0f1;

		@apply helpiefaq-border-gray-300 helpiefaq-border-solid helpiefaq-border helpiefaq-top-0 helpiefaq-left-0 helpiefaq-z-10  helpiefaq-overflow-x-hidden helpiefaq-h-full helpiefaq-relative helpiefaq-transition-all helpiefaq-duration-500;
	}

	.helpiefaq__group__settings {
		@apply helpiefaq-mt-12 helpiefaq-ml-2 helpiefaq-mr-2 helpiefaq-mb-2 helpiefaq-border-2 helpiefaq-border-solid helpiefaq-border-gray-300 helpiefaq-rounded-lg helpiefaq-p-2 helpiefaq-bg-white;
	}

	.helpiefaq__title {
		@apply helpiefaq-text-gray-700 helpiefaq-font-bold helpiefaq-text-xl helpiefaq-ml-1;
	}

	.helpiefaq__label {
		@apply helpiefaq-block helpiefaq-text-sm helpiefaq-font-medium helpiefaq-text-gray-700 helpiefaq-mt-1 helpiefaq-mb-1;
	}

	.helpiefaq__heading {
		@apply helpiefaq-block helpiefaq-text-base helpiefaq-font-semibold helpiefaq-text-gray-600 helpiefaq-mt-1 helpiefaq-mb-1;
	}

	.helpiefaq__fields__container {
		@apply helpiefaq-flex helpiefaq-flex-wrap;
	}

	.helpiefaq__input-group {
		padding: 0 0.5rem 0 0;
	}
</style>
