<template>
	<VCard class="pb-10" title="Lista de distribuidores">
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
			this.getProveedores()
		},
		methods: {
			getProveedores() {
				this.loadingProveedores = true;
				axios
					.get(`api/get-proveedores`)
					.then((res) => {
						this.proveedores = res.data;
					})
					.catch((err) => {
						const st = err.response?.status;
						const msg = err.response?.data?.error;
						if (st === 401) {
							$toast.error('Sesión no válida. Vuelva a iniciar sesión.');
						} else if (st === 403) {
							$toast.error(
								msg ||
									'Sin permiso: si es gestor, seleccione una empresa en el menú superior.'
							);
						} else if (!err.response) {
							$toast.error(
								'Sin conexión con el servidor. Compruebe la red e inténtelo de nuevo.'
							);
						} else {
							$toast.error('Error consultando distribuidores');
						}
					})
					.finally(() => {
						this.loadingProveedores = false;
					});
			},
			
			// Método llamado cuando cambia el cliente seleccionado
			onClienteChanged(event) {
				console.log('Cliente cambiado, recargando proveedores...', event.detail);
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
					$toast.sucs('Distribuidor eliminado')
					this.item= '';
				}, err => {
					$toast.error('Error eliminando distribuidor')
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
