import Vue from 'vue';
// search-icon.
new Vue( {
	el: '#ys-search-icons',
	data: {
		keyword: '',
		icons: searchIcons
	},
	methods: {
		copy: function ( target, done ) {
			if ( this.$refs[ target ] ) {
				this.$refs[ done ][ 0 ].classList.remove( 'is-show' );
				this.$refs[ target ][ 0 ].focus();
				this.$refs[ target ][ 0 ].select();
				document.execCommand( 'copy' );
				this.$refs[ done ][ 0 ].classList.add( 'is-show' );
			}
		}
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

