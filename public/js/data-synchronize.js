(()=>{var t,e={237:()=>{function t(t,n){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var a,r,o,i,l=[],c=!0,u=!1;try{if(o=(n=n.call(t)).next,0===e){if(Object(n)!==n)return;c=!1}else for(;!(c=(a=o.call(n)).done)&&(l.push(a.value),l.length!==e);c=!0);}catch(t){u=!0,r=t}finally{try{if(!c&&null!=n.return&&(i=n.return(),Object(i)!==i))return}finally{if(u)throw r}}return l}}(t,n)||function(t,n){if(!t)return;if("string"==typeof t)return e(t,n);var a=Object.prototype.toString.call(t).slice(8,-1);"Object"===a&&t.constructor&&(a=t.constructor.name);if("Map"===a||"Set"===a)return Array.from(t);if("Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a))return e(t,n)}(t,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function e(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,a=new Array(e);n<e;n++)a[n]=t[n];return a}$((function(){var e=function(e,n,a){var r=$httpClient.make().withResponseType("blob");a&&r.withButtonLoading(a),r.post(e.prop("action"),n).then((function(n){var a=n.headers,r=n.data,o=t(a["content-disposition"].split("filename="),2),i=(o[0],o[1]),l=window.URL.createObjectURL(r),c=document.createElement("a");c.href=l,c.download=i,c.click(),window.URL.revokeObjectURL(l),Botble.showSuccess(e.data("success-message"))})).catch((function(){Botble.showError(e.data("error-message"))}))},n=$(document).find('[data-bb-toggle="import-form"]');if(n.length>0){var a=n.find('button[type="submit"]'),r=n.find('[data-bb-toggle="import-errors"]'),o=n.find(".data-synchronize-import-output"),i=[],l=null,c=function(t,e){e?o.append('<p class="text-'.concat(e,'">').concat(t,"</p>")):o.append("<p>".concat(t,"</p>")),o.scrollTop(o[0].scrollHeight)},u=new Dropzone(n.find(".dropzone").get(0),{url:n.prop("action"),headers:{"X-CSRF-TOKEN":n.find('input[name="_token"]').val()},previewTemplate:$("#data-synchronize-import-preview-template").html(),acceptedFiles:n.data("accepted-files"),maxFiles:1,autoProcessQueue:!1,chunking:!0,chunkSize:1048576,maxfilesexceeded:function(t){this.removeAllFiles(),this.addFile(t)}}),s=function(){Botble.hideButtonLoading(a),a.prop("disabled",!0).addClass("disabled"),u.removeAllFiles()},d=function t(e,a){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:n.data("chunk-size"),o=arguments.length>3&&void 0!==arguments[3]?arguments[3]:0;$httpClient.make().post(n.data("import-url"),{file_name:e,offset:a,limit:r,total:o}).then((function(n){var a=n.data;a.data.count>0?(c(a.message),t(e,a.data.offset+r,r,a.data.total)):(c(a.message,"success"),s())})).catch((function(){return s()}))},f=function t(e,a){var o=arguments.length>2&&void 0!==arguments[2]?arguments[2]:n.data("chunk-size");$httpClient.make().post(n.data("validate-url"),{file_name:e,offset:a,limit:o}).then((function(e){var a=e.data;a.data.errors.length>0&&(i=i.concat(a.data.errors)),null===l&&(l=a.data.total),c(a.message),a.data.count>0?t(a.data.file_name,a.data.offset+o,o):0===i.length?d(a.data.file_name,0):c(n.data("validate-failed-message"),"danger"),i.length>0&&(r.find("ul").html(i.map((function(t){return"<li>".concat(t,"</li>")})).join("")),r.show(),s())})).catch((function(){return s()}))};u.on("sending",(function(){o.empty(),o.show(),c(n.data("uploading-message")),Botble.showButtonLoading(a)})),u.on("success",(function(t,e){var n=e.data,a=e.error,r=e.message;if(a)return c(r,"danger"),void s();c(r),f(n.file_name,0)})),u.on("addedfile",(function(){a.prop("disabled",!1).removeClass("disabled")})),n.on("submit",(function(t){t.preventDefault(),u.getQueuedFiles().length>0&&u.processQueue()}))}$(document).on("click",'[data-bb-toggle="export-data"] button',(function(t){t.preventDefault();var n=$(t.currentTarget),a=n.closest("form");e(a,a.serialize(),n)})).on("click",'[data-bb-toggle="quick-export"]',(function(t){t.preventDefault(),e($('[data-bb-toggle="export-data"]'),{format:$(t.currentTarget).data("value")})}))}))},710:()=>{}},n={};function a(t){var r=n[t];if(void 0!==r)return r.exports;var o=n[t]={exports:{}};return e[t](o,o.exports,a),o.exports}a.m=e,t=[],a.O=(e,n,r,o)=>{if(!n){var i=1/0;for(s=0;s<t.length;s++){for(var[n,r,o]=t[s],l=!0,c=0;c<n.length;c++)(!1&o||i>=o)&&Object.keys(a.O).every((t=>a.O[t](n[c])))?n.splice(c--,1):(l=!1,o<i&&(i=o));if(l){t.splice(s--,1);var u=r();void 0!==u&&(e=u)}}return e}o=o||0;for(var s=t.length;s>0&&t[s-1][2]>o;s--)t[s]=t[s-1];t[s]=[n,r,o]},a.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),(()=>{var t={232:0,287:0};a.O.j=e=>0===t[e];var e=(e,n)=>{var r,o,[i,l,c]=n,u=0;if(i.some((e=>0!==t[e]))){for(r in l)a.o(l,r)&&(a.m[r]=l[r]);if(c)var s=c(a)}for(e&&e(n);u<i.length;u++)o=i[u],a.o(t,o)&&t[o]&&t[o][0](),t[o]=0;return a.O(s)},n=self.webpackChunk=self.webpackChunk||[];n.forEach(e.bind(null,0)),n.push=e.bind(null,n.push.bind(n))})(),a.O(void 0,[287],(()=>a(237)));var r=a.O(void 0,[287],(()=>a(710)));r=a.O(r)})();