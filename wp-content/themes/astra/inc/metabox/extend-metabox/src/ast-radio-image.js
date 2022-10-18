import {Fragment} from '@wordpress/element';
import svgIcons from '../../../../assets/svg/svgs.json';

const AstRadioImageControl = props => {

	const {
		label,
		description,
		id,
		choices,
		metavalue
	} = props;

	const onLayoutChange = (value) => {
		props.onChange( value );
	};

	let htmlLabel = null,
		htmlDescription = null,
		htmlRadio;

	const counterClass = Object.keys( choices ).length ? 'ast-radio-option-' + Object.keys( choices ).length :'';

	if (label) {
		htmlLabel = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		htmlDescription = <span className="description customize-control-description">{description}</span>;
	}

	htmlRadio = Object.entries(choices).map(([key, data]) => {
		let value = data.value;
		let checked = metavalue === value ? true : false;

		return (
			<Fragment key={key}>
				<input className="image-select" type="radio" value={value} name={id}
						id={id + '-' + value} checked={checked} onChange={() => onLayoutChange(value)}/>
				<label htmlFor={id + '-' + value} className="ast-radio-img-svg">
					<span className='ast-meta-image-tooltip'>
						{ data.label }
					</span>
					<span dangerouslySetInnerHTML={{
						__html: svgIcons[ value ]
					}}/>
				</label>
			</Fragment>
		);
	});

	return <div className="ast-radio-image-controller">
		{ ( htmlLabel || htmlDescription ) &&
			<label>
				{htmlLabel}
				{htmlDescription}
			</label>
		}
		<div id={`input_${id}`} className={`options-wrapper ${ counterClass }`}>
			{htmlRadio}
		</div>
	</div>;
};

export default AstRadioImageControl;
