import Vue from 'vue';
// search-icon.
new Vue( {
	el: '#ys-search-icons',
	data: {
		keyword: '',
		icons: searchIcons,
	},
	computed: {
		filteredIcons() {
			let result = [];
			if ( ! this.keyword.trim() ) {
				return this.icons;
			}
			for ( var i in this.icons ) {
				var icon = this.icons[ i ];
				if ( icon.name.indexOf( this.keyword ) !== -1 ) {
					result.push( icon );
				}
			}
			return result;
		}
	}
} );

