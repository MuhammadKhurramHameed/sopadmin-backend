(this.webpackJsonpemilus=this.webpackJsonpemilus||[]).push([[27],{230:function(e,t,n){"use strict";n.r(t);var a=n(71),r=n.n(a),c=n(110),o=n(151),l=n(0),i=n.n(l),s=n(70),u=n(354),d=n(111),p=n(108),f=n(416),m=n(331),b=n(332),v=n(537),g=n(405),y=n(406),h=n(384),E=n(24),k=n(95),C=n.n(k);t.default=Object(E.b)((function(e){return{user_id:e.auth.user.user_id,role_id:e.auth.user.role_id}}))((function(e){var t=e.user_id,n=e.history,a=e.match,l=i.a.useState(!0),E=Object(o.a)(l,2),k=E[0],x=E[1],O=i.a.useState([]),j=Object(o.a)(O,2),w=j[0],_=j[1],N=a.params.order_token,P=function(){var e=Object(c.a)(r.a.mark((function e(){var n;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,C.a.post("/orderlist",{user_id:t,order_token:N});case 3:n=e.sent,_(n.data.data),x(!1),e.next=13;break;case 8:e.prev=8,e.t0=e.catch(0),x(!1),console.log(e.t0),e.t0.response?s.b.success(e.t0.response.data):s.b.error(e.t0.message);case 13:case"end":return e.stop()}}),e,null,[[0,8]])})));return function(){return e.apply(this,arguments)}}(),S=function(){var e=Object(c.a)(r.a.mark((function e(n){var a;return r.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,C.a.post("/deleteorder",{user_id:t,order_id:n});case 3:a=e.sent,s.b.success(a.data.message),P(),e.next=12;break;case 8:e.prev=8,e.t0=e.catch(0),console.log(e.t0),e.t0.response?s.b.error(e.t0.response.data):s.b.error(e.t0.message);case 12:case"end":return e.stop()}}),e,null,[[0,8]])})));return function(t){return e.apply(this,arguments)}}();i.a.useEffect((function(){P()}),[]);var T=[{title:"Reference No.",dataIndex:"order_token",key:"order_token",align:"center",render:function(e){return i.a.createElement("span",null,i.a.createElement(u.a,null,e))}},{title:"Design Name",dataIndex:"order_designname",key:"order_token",align:"center"},{title:"Design Type",dataIndex:"order_designtype",key:"order_token",align:"center"},{title:"Work By",dataIndex:"user_name",key:"user_name",align:"center",render:function(e){return i.a.createElement("span",null,e||"-")}},{title:"Status",dataIndex:"order_status",key:"order_status",align:"center",render:function(e){var t="";return"Pending"===e?t="geekblue":"Assigned"===e?t="blue":"In Progress"===e?t="purple":"Completed"===e?t="cyan":"Back To Agent"===e?t="volcano":"Agent Fixed"===e?t="orange":"Back To Manager"===e?t="gold":"Edit By Client"===e?t="red":"Assign Edit"===e&&(t="magenta"),i.a.createElement("span",null,i.a.createElement(u.a,{color:t},e))}},{title:"Action",key:"action",align:"center",render:function(e,t){return i.a.createElement("div",null,i.a.createElement(d.a,{title:"Edit"},i.a.createElement(p.a,{type:"primary",className:"mr-2",icon:i.a.createElement(y.a,null),size:"small",onClick:function(){return n.push("/orders/".concat(N,"/").concat(t.order_id))}})),"Pending"===t.order_status&&i.a.createElement(f.a,{placement:"topRight",title:"Are you sure, you want to delete this? ",onConfirm:function(){return S(t.order_id)},okText:"Yes",cancelText:"No"},i.a.createElement(d.a,{title:"Remove"},i.a.createElement(p.a,{danger:!0,icon:i.a.createElement(h.a,null),size:"small"}))))}}];return i.a.createElement(i.a.Fragment,null,i.a.createElement(m.a,{style:{justifyContent:"center"}},i.a.createElement(b.a,{lg:22},i.a.createElement(v.a,{bodyStyle:{padding:"0px"}},i.a.createElement(g.a,{columns:T,dataSource:w,loading:k,rowKey:"order_id"})))))}))},354:function(e,t,n){"use strict";var a=n(4),r=n.n(a),c=n(2),o=n.n(c),l=n(18),i=n.n(l),s=n(0),u=n(3),d=n.n(u),p=n(45),f=n(109),m=n.n(f),b=n(57),v=function(e,t){var n={};for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&t.indexOf(a)<0&&(n[a]=e[a]);if(null!=e&&"function"===typeof Object.getOwnPropertySymbols){var r=0;for(a=Object.getOwnPropertySymbols(e);r<a.length;r++)t.indexOf(a[r])<0&&Object.prototype.propertyIsEnumerable.call(e,a[r])&&(n[a[r]]=e[a[r]])}return n},g=function(e){var t,n=e.prefixCls,a=e.className,c=e.checked,l=e.onChange,i=e.onClick,u=v(e,["prefixCls","className","checked","onChange","onClick"]),p=(0,s.useContext(b.b).getPrefixCls)("tag",n),f=d()(p,(t={},r()(t,"".concat(p,"-checkable"),!0),r()(t,"".concat(p,"-checkable-checked"),c),t),a);return s.createElement("span",o()({},u,{className:f,onClick:function(e){l&&l(!c),i&&i(e)}}))},y=n(154),h=n(158),E=function(e,t){var n={};for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&t.indexOf(a)<0&&(n[a]=e[a]);if(null!=e&&"function"===typeof Object.getOwnPropertySymbols){var r=0;for(a=Object.getOwnPropertySymbols(e);r<a.length;r++)t.indexOf(a[r])<0&&Object.prototype.propertyIsEnumerable.call(e,a[r])&&(n[a[r]]=e[a[r]])}return n},k=new RegExp("^(".concat(y.a.join("|"),")(-inverse)?$")),C=new RegExp("^(".concat(y.b.join("|"),")$")),x=function(e,t){var n,a=e.prefixCls,c=e.className,l=e.style,u=e.children,f=e.icon,v=e.color,g=e.onClose,y=e.closeIcon,x=e.closable,O=void 0!==x&&x,j=E(e,["prefixCls","className","style","children","icon","color","onClose","closeIcon","closable"]),w=s.useContext(b.b),_=w.getPrefixCls,N=w.direction,P=s.useState(!0),S=i()(P,2),T=S[0],I=S[1];s.useEffect((function(){"visible"in j&&I(j.visible)}),[j.visible]);var z=function(){return!!v&&(k.test(v)||C.test(v))},R=o()({backgroundColor:v&&!z()?v:void 0},l),B=z(),V=_("tag",a),A=d()(V,(n={},r()(n,"".concat(V,"-").concat(v),B),r()(n,"".concat(V,"-has-color"),v&&!B),r()(n,"".concat(V,"-hidden"),!T),r()(n,"".concat(V,"-rtl"),"rtl"===N),n),c),D=function(e){e.stopPropagation(),g&&g(e),e.defaultPrevented||"visible"in j||I(!1)},H="onClick"in j||u&&"a"===u.type,F=Object(p.a)(j,["visible"]),K=f||null,M=K?s.createElement(s.Fragment,null,K,s.createElement("span",null,u)):u,J=s.createElement("span",o()({},F,{ref:t,className:A,style:R}),M,O?y?s.createElement("span",{className:"".concat(V,"-close-icon"),onClick:D},y):s.createElement(m.a,{className:"".concat(V,"-close-icon"),onClick:D}):null);return H?s.createElement(h.a,null,J):J},O=s.forwardRef(x);O.displayName="Tag",O.CheckableTag=g;t.a=O},384:function(e,t,n){"use strict";var a=n(0),r={icon:{tag:"svg",attrs:{viewBox:"64 64 896 896",focusable:"false"},children:[{tag:"path",attrs:{d:"M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"}}]},name:"delete",theme:"outlined"},c=n(38),o=function(e,t){return a.createElement(c.a,Object.assign({},e,{ref:t,icon:r}))};o.displayName="DeleteOutlined";t.a=a.forwardRef(o)},416:function(e,t,n){"use strict";var a=n(2),r=n.n(a),c=n(18),o=n.n(c),l=n(0),i=n(3),s=n.n(i),u=n(156),d=n.n(u),p=n(50),f=n(111),m=n(108),b=n(155),v=n(106),g=n(72),y=n(57),h=n(136),E=n(27),k=function(e,t){var n={};for(var a in e)Object.prototype.hasOwnProperty.call(e,a)&&t.indexOf(a)<0&&(n[a]=e[a]);if(null!=e&&"function"===typeof Object.getOwnPropertySymbols){var r=0;for(a=Object.getOwnPropertySymbols(e);r<a.length;r++)t.indexOf(a[r])<0&&Object.prototype.propertyIsEnumerable.call(e,a[r])&&(n[a[r]]=e[a[r]])}return n},C=l.forwardRef((function(e,t){var n=l.useState(e.visible),a=o()(n,2),c=a[0],i=a[1];l.useEffect((function(){"visible"in e&&i(e.visible)}),[e.visible]),l.useEffect((function(){"defaultVisible"in e&&i(e.defaultVisible)}),[e.defaultVisible]);var u=function(t,n){"visible"in e||i(t),e.onVisibleChange&&e.onVisibleChange(t,n)},d=function(t){u(!1,t),e.onConfirm&&e.onConfirm.call(void 0,t)},C=function(t){u(!1,t),e.onCancel&&e.onCancel.call(void 0,t)},x=l.useContext(y.b).getPrefixCls,O=e.prefixCls,j=e.placement,w=e.children,_=e.overlayClassName,N=k(e,["prefixCls","placement","children","overlayClassName"]),P=x("popover",O),S=x("popconfirm",O),T=s()(S,_),I=l.createElement(v.a,{componentName:"Popconfirm",defaultLocale:g.a.Popconfirm},(function(t){return function(t,n){var a=e.okButtonProps,c=e.cancelButtonProps,o=e.title,i=e.cancelText,s=e.okText,u=e.okType,p=e.icon;return l.createElement("div",{className:"".concat(t,"-inner-content")},l.createElement("div",{className:"".concat(t,"-message")},p,l.createElement("div",{className:"".concat(t,"-message-title")},Object(h.a)(o))),l.createElement("div",{className:"".concat(t,"-buttons")},l.createElement(m.a,r()({onClick:C,size:"small"},c),i||n.cancelText),l.createElement(m.a,r()({onClick:d},Object(b.a)(u),{size:"small"},a),s||n.okText)))}(P,t)}));return l.createElement(f.a,r()({},N,{prefixCls:P,placement:j,onVisibleChange:function(t){e.disabled||u(t)},visible:c,overlay:I,overlayClassName:T,ref:t}),Object(E.a)(w,{onKeyDown:function(e){var t,n;l.isValidElement(w)&&(null===(n=null===w||void 0===w?void 0:(t=w.props).onKeyDown)||void 0===n||n.call(t,e)),function(e){e.keyCode===p.a.ESC&&c&&u(!1,e)}(e)}}))}));C.defaultProps={transitionName:"zoom-big",placement:"top",trigger:"click",okType:"primary",icon:l.createElement(d.a,null),disabled:!1},t.a=C}}]);
//# sourceMappingURL=27.cc68719f.chunk.js.map