!function(){var e,t={911:function(e,t,n){"use strict";var r=window.wp.blocks,o=window.wp.element,a=window.wp.blockEditor,l=window.wp.components,i=window.wp.apiFetch,c=n.n(i),s=window.wp.data,u=window.wp.i18n,p=window.wp.primitives,f=(0,o.createElement)(p.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,o.createElement)(p.Path,{d:"M4 4v1.5h16V4H4zm8 8.5h8V11h-8v1.5zM4 20h16v-1.5H4V20zm4-8c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"})),d=(0,o.createElement)(p.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,o.createElement)(p.Path,{d:"M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7.8 16.5H5c-.3 0-.5-.2-.5-.5v-6.2h6.8v6.7zm0-8.3H4.5V5c0-.3.2-.5.5-.5h6.2v6.7zm8.3 7.8c0 .3-.2.5-.5.5h-6.2v-6.8h6.8V19zm0-7.8h-6.8V4.5H19c.3 0 .5.2.5.5v6.2z",fillRule:"evenodd",clipRule:"evenodd"})),h=n(779),m=n.n(h);function v(){return v=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},v.apply(this,arguments)}var w=(0,o.memo)((function(e){let{blocks:t,blockContextId:n,isHidden:r,setActiveBlockContextId:l}=e;const i=(0,a.__experimentalUseBlockPreview)({blocks:t,props:{className:"wp-block-cap-coauthor"}}),c=()=>{l(n)},s={display:r?"none":void 0};return(0,o.createElement)("div",v({},i,{tabIndex:0,role:"button",onClick:c,onKeyUp:c,style:s}))}));function b(){return(0,o.createElement)("div",(0,a.useInnerBlocksProps)({className:"wp-block-cap-coauthor"},{template:[["cap/coauthor-display-name"]]}))}const y=["core/bold","core/image","core/italic","core/link","core/strikethrough","core/text-color"];var g=JSON.parse('{"u2":"cap/coauthors"}');(0,r.registerBlockType)(g.u2,{edit:function(e){let{attributes:t,setAttributes:n,clientId:r,context:i,isSelected:p}=e;const{postId:h}=i,[v,g]=(0,o.useState)([{id:0,displayName:"CoAuthor Display Name"}]),[k,x]=(0,o.useState)(),_=(0,s.useDispatch)("core/notices"),{separator:E,lastSeparator:C,layout:O,prefix:S,suffix:B}=t;function N(e){"AbortError"!==e.name&&_.createErrorNotice(e.message,{isDismissible:!0})}(0,o.useEffect)((()=>{if(!h)return;const e=new AbortController;return c()({path:`/coauthors/v1/authors/${h}`,signal:e.signal}).then(g).catch(N),()=>{e.abort()}}),[h]);const P=(0,s.useSelect)((e=>e(a.store).getBlocks(r))),A=e=>{n({layout:{...O,...e}})},I=[{icon:f,title:(0,u.__)("Inline"),onClick:()=>A({type:"inline"}),isActive:"inline"===O.type},{icon:d,title:(0,u.__)("Block"),onClick:()=>A({type:"block"}),isActive:"block"===O.type}];return(0,o.createElement)(o.Fragment,null,(0,o.createElement)(a.BlockControls,null,(0,o.createElement)(l.ToolbarGroup,{controls:I})),(0,o.createElement)("div",(0,a.useBlockProps)({className:m()([`is-layout-cap-${O.type}`])}),v&&"inline"===O.type&&(p||S)&&(0,o.createElement)(a.RichText,{allowedFormats:y,className:"wp-block-cap-coauthor__prefix",multiline:!1,"aria-label":(0,u.__)("Prefix"),placeholder:(0,u.__)("Prefix")+" ",value:S,onChange:e=>n({prefix:e}),tagName:"span"}),v&&v.map((e=>{var t;let{id:n,displayName:r}=e;const l=n===(k||(null===(t=v[0])||void 0===t?void 0:t.id));return(0,o.createElement)(a.BlockContextProvider,{key:n,value:{coAuthorId:n,displayName:r}},l?(0,o.createElement)(b,null):null,(0,o.createElement)(w,{blocks:P,blockContextId:n,setActiveBlockContextId:x,isHidden:l}))})).reduce(((e,t,n,r)=>(0,o.createElement)(o.Fragment,null,e,"inline"===O.type&&(0,o.createElement)("span",{className:"wp-block-cap-coauthor__separator"},C&&n===r.length-1?`${C}`:`${E}`),t))),v&&"inline"===O.type&&(p||B)&&(0,o.createElement)(a.RichText,{allowedFormats:y,className:"wp-block-cap-coauthor__suffix",multiline:!1,"aria-label":(0,u.__)("Suffix"),placeholder:(0,u.__)("Suffix")+" ",value:B,onChange:e=>n({suffix:e}),tagName:"span"})),(0,o.createElement)(a.InspectorControls,null,(0,o.createElement)(l.PanelBody,{title:(0,u.__)("CoAuthors Layout")},"inline"===O.type&&(0,o.createElement)(o.Fragment,null,(0,o.createElement)(l.TextControl,{autoComplete:"off",label:(0,u.__)("Separator"),value:E||"",onChange:e=>{n({separator:e})},help:(0,u.__)("Enter character(s) used to separate authors.")}),(0,o.createElement)(l.TextControl,{autoComplete:"off",label:(0,u.__)("Last Separator"),value:C||"",onChange:e=>{n({lastSeparator:e})},help:(0,u.__)("Enter character(s) used to distinguish the last author.")})))))},save:function(){return(0,o.createElement)(a.InnerBlocks.Content,null)}})},779:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var a=typeof n;if("string"===a||"number"===a)e.push(n);else if(Array.isArray(n)){if(n.length){var l=o.apply(null,n);l&&e.push(l)}}else if("object"===a){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var i in n)r.call(n,i)&&n[i]&&e.push(i)}}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()}},n={};function r(e){var o=n[e];if(void 0!==o)return o.exports;var a=n[e]={exports:{}};return t[e](a,a.exports,r),a.exports}r.m=t,e=[],r.O=function(t,n,o,a){if(!n){var l=1/0;for(u=0;u<e.length;u++){n=e[u][0],o=e[u][1],a=e[u][2];for(var i=!0,c=0;c<n.length;c++)(!1&a||l>=a)&&Object.keys(r.O).every((function(e){return r.O[e](n[c])}))?n.splice(c--,1):(i=!1,a<l&&(l=a));if(i){e.splice(u--,1);var s=o();void 0!==s&&(t=s)}}return t}a=a||0;for(var u=e.length;u>0&&e[u-1][2]>a;u--)e[u]=e[u-1];e[u]=[n,o,a]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,431:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var o,a,l=n[0],i=n[1],c=n[2],s=0;if(l.some((function(t){return 0!==e[t]}))){for(o in i)r.o(i,o)&&(r.m[o]=i[o]);if(c)var u=c(r)}for(t&&t(n);s<l.length;s++)a=l[s],r.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return r.O(u)},n=self.webpackChunk=self.webpackChunk||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var o=r.O(void 0,[431],(function(){return r(911)}));o=r.O(o)}();