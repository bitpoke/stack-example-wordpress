import { Button } from '@wordpress/components';

const AstSelectorControl = props => {

	const {
		label,
		description,
		id,
		metavalue,
		choices,
	} = props;

	const onValueChange = (value) => {
		props.onChange( value );
	};

	if ( ! choices ) {
		return;
	}

	let labelHtml = null;
	let descriptionHtml = null;

	if ( label ) {
		labelHtml = <span className="ast-meta-sortable-title">{label}</span>;
	}

	if ( description ) {
		descriptionHtml = <span className="ast-meta-sortable-description">{description}</span>;
	}

	let optionsHtml = Object.entries( choices ).map( ( [key, data] ) => {

		let value = data.value;

		var html = (
			<div className="ast-selector-inner-wrap" key={ key }>
				<Button
					key={ key }
					onClick={ () => onValueChange( value ) }
					aria-pressed = { value === metavalue }
					isPrimary = { value === metavalue }
					label = { data.label }
				>
					{ data.label }
				</Button>
			</div>
		);

		return html;
	} );

	return <div id={id} className='ast-meta-selector-controller'>
		{ ( labelHtml || descriptionHtml ) &&
			<label>
				{labelHtml}
				{descriptionHtml}
			</label>
		}
		<div className="ast-meta-selector-wrapper">
			{optionsHtml}
		</div>
	</div>;

};

export default AstSelectorControl;
