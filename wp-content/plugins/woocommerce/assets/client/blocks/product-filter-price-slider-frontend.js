var wc;(()=>{"use strict";var e={};(e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})})(e);const t=window.wc.__experimentalInteractivity;(0,t.store)("woocommerce/product-filter-price-slider",{state:{rangeStyle:()=>{const{minRange:e,maxRange:r}=(0,t.getContext)("woocommerce/product-filter-price"),o=(0,t.store)("woocommerce/product-filter-price"),{minPrice:c,maxPrice:i}=o.state;return`--low: ${100*(c-e)/(r-e)}%; --high: ${100*(i-e)/(r-e)}%;`}},actions:{selectInputContent:()=>{const e=(0,t.getElement)();e&&e.ref&&e.ref.select()},debounceSetPrice:((e,t,r)=>{let o,c=null;const i=(...t)=>{c=t,o&&clearTimeout(o),o=setTimeout((()=>{o=null,c&&e(...c)}),1e3)};return i.flush=()=>{o&&c&&(e(...c),clearTimeout(o),o=null)},i})((e=>{e.target.dispatchEvent(new Event("change"))})),limitRange:e=>{const r=(0,t.store)("woocommerce/product-filter-price"),{minPrice:o,maxPrice:c}=r.state;e.target.classList.contains("min")?e.target.value=Math.min(parseInt(e.target.value,10),c-1).toString():e.target.value=Math.max(parseInt(e.target.value,10),o+1).toString()}}}),(wc=void 0===wc?{}:wc)["product-filter-price-slider"]=e})();