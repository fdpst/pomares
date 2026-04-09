<template>
	<VCard class="pb-10" title="Lista de proveedores">
		<div class="ps-5 pe-5 pb-5">
			<ConfirmDialog
				v-model="modalEliminar"
				@cancel="closeModal"
				@confirm="deleteProveedor"
				color="primary"
			/>

			<loader v-if="isloading || loadingProveedores"></loader>

			<VRow>
				<VCol cols="12" md="8">
					<VTextField prepend-icon="ri-user-search-fill" v-model="search" label="Búsqueda"></VTextField>
				</VCol>
				<VCol cols="12" md="4" class="text-end">
					<VBtn 
						rounded 
						depressed 
						color="primary" 
						class="mt-1" 
						:to="{ path: `/guardar-proveedor` }"
					>
						Nuevo
					</VBtn>
				</VCol>
			</VRow>
		</div>

		<VDataTable :headers="headers" :items="proveedores" :search="search" item-key="id" class="elevation-1 mt-3">
			<template v-slot:item.action="{ item }">
				<RouterLink :to="`/guardar-proveedor?id=${item.id}`" class="action-buttons">
					<VIcon small class="mr-2" color="grey-600">
						ri-pencil-line
					</VIcon>
				</RouterLink>

				<VIcon @click="mostrarModalEliminar(item)" small class="mr-2" color="grey-600">
					ri-delete-bin-line
				</VIcon>

			</template>
		</VDataTable>
	</VCard>
</template>



<script>
	import gestorClienteMixin from '@/global_mixins/gestorClienteMixin.js'
	
	export default {
		mixins: [gestorClienteMixin],
		
		data() {
			return {
				 modalEliminar: false,
				item:'',
				search: '',
				proveedores: [],
				loadingProveedores: false,
				headers: [
					{
						title: 'N°',
						value: 'nro_proveedor'
					},
					{
						title: 'Nombre',
						value: 'nombre'
					},
					{
						title: 'Email',
						value: 'email'
					},
					{
						title: 'Teléfono',
						value: 'telefono'
					},
					{
						title: 'Fecha',
						value: 'created_at'
					},
					{
						title: 'Acciones',
						value: 'action',
						sortable: false
					},
				],
			}
		},
		created() {
			console.log('ListaProveedores: created() ejecutado');
			this.getProveedores()
		},
		
		mounted() {
			console.log('ListaProveedores: mounted() ejecutado');
			console.log('ListaProveedores: onClienteChanged disponible?', typeof this.onClienteChanged === 'function');
		},
		methods: {
			getProveedores() {
				// No pasar el user_id en la URL, el backend lo determinará automáticamente
				this.loadingProveedores = true;
				axios.get(`api/get-proveedores`).then(res => {
					this.proveedores = res.data;
					this.loadingProveedores = false;
				}, err => {
					$toast.error('Error consultando proveedores');
					this.loadingProveedores = false;
				})
			},
			
			// Método llamado cuando cambia el cliente seleccionado
			onClienteChanged(event) {
				console.log('Cliente cambiado, recargando proveedores...', event.detail);
				// Limpiar la lista mientras se cargan los nuevos datos
				this.proveedores = [];
				this.getProveedores();
			},
			mostrarModalEliminar(item){
				this.modalEliminar = true;
				this.item = item;
			},
			closeModal(){
				this.modalEliminar = false;
				this.item = '';
			},
			deleteProveedor() {
				 this.modalEliminar = false;
				axios.get(`api/delete-proveedor/${this.item.id}`).then(res => {
					this.proveedores.splice(this.proveedores.indexOf(this.item), 1)
					$toast.sucs('Proveedor eliminado')
					this.item= '';
				}, err => {
					$toast.error('Error eliminando proveedor')
				})
			}
		},
		computed: {
			isloading: function() {
				return this.$store.getters.getloading
			},
		}
	}
</script>
