// app/editarPet.tsx

import React, { useState, useEffect, useMemo, useCallback } from 'react';
import {
    StyleSheet,
    View,
    Text,
    TextInput,
    TouchableOpacity,
    KeyboardAvoidingView,
    Platform,
    ScrollView,
    Alert,
    ActivityIndicator,
    Modal,
    FlatList,
} from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { useAuth, API_URL } from '@/app/context/AuthContext';
import { PET_DATA } from '@/data/databasePets';
import { HEALTH_DATA, HealthItem } from '@/data/databaseMedi';
import HistoricoItemCard from '@/app/components/HistoricoItemCard';
import RNDateTimePicker, { DateTimePickerEvent } from '@react-native-community/datetimepicker';

interface HistoricoSaudeItem {
    id: string;
    name: string;
    data_aplicacao: string;
    data_desconhecida: boolean;
}

export default function EditPetScreen() {
    const router = useRouter();
    const { getAuthHeader } = useAuth();
    const { petId } = useLocalSearchParams();
    
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);

    // --- Estados do Formulário ---
    const [nome, setNome] = useState('');
    const [especie, setEspecie] = useState('');
    const [raca, setRaca] = useState('');
    
    // Data e Idade
    const [dataNascimento, setDataNascimento] = useState('');
    const [naoSeiDataNascimento, setNaoSeiDataNascimento] = useState(false);
    const [idadeAprox, setIdadeAprox] = useState('');
    const [unidadeIdade, setUnidadeIdade] = useState<'anos' | 'meses'>('anos');
    const [idadeMesesExtra, setIdadeMesesExtra] = useState('');
    const [naoSeiMeses, setNaoSeiMeses] = useState(false);
    const [idadeDiasExtra, setIdadeDiasExtra] = useState('');
    const [naoSeiDias, setNaoSeiDias] = useState(false);

    // Outros dados
    const [sexo, setSexo] = useState('');
    const [peso, setPeso] = useState('');
    const [naoSeiPeso, setNaoSeiPeso] = useState(false);
    const [cor, setCor] = useState('');

    // Saúde
    const [historicoVacinas, setHistoricoVacinas] = useState<HistoricoSaudeItem[]>([]);
    const [historicoMedicamentos, setHistoricoMedicamentos] = useState<HistoricoSaudeItem[]>([]);

    // Controles de UI
    const [showDatePicker, setShowDatePicker] = useState(false);
    const [datePickerDate, setDatePickerDate] = useState(new Date());
    const [modalVisible, setModalVisible] = useState(false);
    const [modalConfig, setModalConfig] = useState<{
        type: 'especie' | 'raca' | 'vacina' | 'medicamento';
        title: string;
    } | null>(null);
    const [busca, setBusca] = useState('');

    // --- CARREGAR DADOS ---
    useEffect(() => {
        if (!petId) return;

        const fetchPetData = async () => {
            try {
                const response = await fetch(`${API_URL}/pets/${petId}`, {
                    headers: getAuthHeader(),
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message);

                // Preencher campos básicos
                setNome(data.nome);
                setEspecie(data.especie);
                setRaca(data.raca);
                setSexo(data.sexo);
                setCor(data.cor || '');
                
                if (data.peso) {
                    setPeso(data.peso.toString());
                } else {
                    setNaoSeiPeso(true);
                }

                // Lógica de Data/Idade
                if (data.data_nascimento) {
                    // Converter AAAA-MM-DD para DD/MM/AAAA
                    const [year, month, day] = data.data_nascimento.split('-');
                    setDataNascimento(`${day}/${month}/${year}`);
                    setNaoSeiDataNascimento(false);
                } else {
                    setNaoSeiDataNascimento(true);
                    if (data.idade_valor) setIdadeAprox(data.idade_valor.toString());
                    if (data.idade_unidade) setUnidadeIdade(data.idade_unidade);
                    if (data.idade_meses) setIdadeMesesExtra(data.idade_meses.toString());
                    if (data.idade_dias) setIdadeDiasExtra(data.idade_dias.toString());
                }

                // Preencher Vacinas
                if (data.vacinas && Array.isArray(data.vacinas)) {
                    const vacinasFormatadas = data.vacinas.map((v: any) => ({
                        id: v.id,
                        name: v.nome,
                        data_aplicacao: v.data_aplicacao ? formatDateToBR(v.data_aplicacao) : '',
                        data_desconhecida: v.data_desconhecida === 1 || v.data_desconhecida === true
                    }));
                    setHistoricoVacinas(vacinasFormatadas);
                }

                // Preencher Medicamentos
                if (data.medicamentos && Array.isArray(data.medicamentos)) {
                    const medsFormatados = data.medicamentos.map((m: any) => ({
                        id: m.id,
                        name: m.nome,
                        data_aplicacao: m.data_aplicacao ? formatDateToBR(m.data_aplicacao) : '',
                        data_desconhecida: m.data_desconhecida === 1 || m.data_desconhecida === true
                    }));
                    setHistoricoMedicamentos(medsFormatados);
                }

            } catch (error: any) {
                Alert.alert('Erro', error.message);
                router.back();
            } finally {
                setIsLoading(false);
            }
        };
        fetchPetData();
    }, [petId]);

    const formatDateToBR = (isoDate: string) => {
        if (!isoDate) return '';
        const [year, month, day] = isoDate.split('T')[0].split('-');
        return `${day}/${month}/${year}`;
    };

    // --- SALVAR DADOS ---
    const handleSave = async () => {
        if (!nome || !especie || !raca || !sexo) {
            Alert.alert('Atenção', 'Nome, Espécie, Raça e Sexo são obrigatórios.');
            return;
        }

        setIsSaving(true);
        
        // Monta o objeto igual ao cadastro
        const payload: any = {
            nome: nome.trim(),
            especie,
            raca,
            sexo,
            cor: cor.trim() || null,
            peso: (!naoSeiPeso && peso) ? peso : null,
        };

        // Lógica de Data para envio
        if (naoSeiDataNascimento) {
            payload.idade_valor = idadeAprox;
            payload.idade_unidade = unidadeIdade;
            if (unidadeIdade === 'anos' && !naoSeiMeses && idadeMesesExtra) payload.idade_meses = idadeMesesExtra;
            if (unidadeIdade === 'meses' && !naoSeiDias && idadeDiasExtra) payload.idade_dias = idadeDiasExtra;
            payload.data_nascimento = null;
        } else {
             if (dataNascimento && dataNascimento.length === 10) {
                payload.data_nascimento = dataNascimento.split('/').reverse().join('-');
                payload.idade_valor = null;
            } else {
                Alert.alert("Erro na Data", "Por favor, insira uma data válida.");
                setIsSaving(false);
                return;
            }
        }

        // Prepara arrays de saúde
        const vacinasParaEnviar = historicoVacinas.map((v) => ({
            id: v.id,
            nome: v.name,
            data_aplicacao: v.data_aplicacao ? v.data_aplicacao.split('/').reverse().join('-') : null,
            data_desconhecida: v.data_desconhecida,
        }));

        const medicamentosParaEnviar = historicoMedicamentos.map((m) => ({
            id: m.id,
            nome: m.name,
            data_aplicacao: m.data_aplicacao ? m.data_aplicacao.split('/').reverse().join('-') : null,
            data_desconhecida: m.data_desconhecida,
        }));
        
        // NOTA: O backend precisa estar preparado para receber 'vacinas' e 'medicamentos' no PUT
        // Caso contrário, apenas os dados do perfil serão atualizados.
        payload.vacinas = vacinasParaEnviar;
        payload.medicamentos = medicamentosParaEnviar;

        try {
            const response = await fetch(`${API_URL}/pets/${petId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', ...getAuthHeader() },
                body: JSON.stringify(payload),
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro ao salvar');
            
            Alert.alert('Sucesso!', 'Dados atualizados com sucesso.', [
                { text: 'OK', onPress: () => router.back() },
            ]);
        } catch (error: any) {
            Alert.alert('Erro ao Salvar', error.message);
        } finally {
            setIsSaving(false);
        }
    };

    // --- DATE PICKER LOGIC ---
    const openDatePicker = () => {
        const parts = dataNascimento.split('/');
        let currentDate = new Date();
        if (parts.length === 3) {
            const [day, month, year] = parts.map(Number);
            if (day && month && year > 1900) currentDate = new Date(year, month - 1, day);
        }
        setDatePickerDate(currentDate);
        setShowDatePicker(true);
    };

    const onChangeDatePicker = (event: DateTimePickerEvent, selectedDate?: Date) => {
        setShowDatePicker(false);
        if (event.type === 'set' && selectedDate) {
            const day = String(selectedDate.getDate()).padStart(2, '0');
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const year = selectedDate.getFullYear();
            setDataNascimento(`${day}/${month}/${year}`);
            setNaoSeiDataNascimento(false);
        }
    };

    // --- HANDLERS AUXILIARES (Copiados do Cadastro) ---
    const handleDataNascimentoChange = (text: string) => {
        let formatted = text.replace(/[^0-9]/g, '');
        if (text.length > dataNascimento.length) {
            if (formatted.length > 2) formatted = formatted.slice(0, 2) + '/' + formatted.slice(2);
            if (formatted.length > 5) formatted = formatted.slice(0, 5) + '/' + formatted.slice(5, 9);
        } else { formatted = text; }
        setDataNascimento(formatted);
    };

    const handleHealthDateChange = (id: string, text: string, list: any[], setList: Function) => {
        setList(list.map(item => {
            if (item.id === id) {
                let formatted = text.replace(/[^0-9]/g, '');
                if (text.length > (item.data_aplicacao || '').length) {
                     if (formatted.length > 2) formatted = formatted.slice(0, 2) + '/' + formatted.slice(2);
                     if (formatted.length > 5) formatted = formatted.slice(0, 5) + '/' + formatted.slice(5, 9);
                } else { formatted = text; }
                return { ...item, data_aplicacao: formatted };
            }
            return item;
        }));
    };

    const handleHealthUnknownToggle = (id: string, list: any[], setList: Function) => {
        setList(list.map(item => item.id === id ? { ...item, data_desconhecida: !item.data_desconhecida, data_aplicacao: '' } : item));
    };

    const handleRemoveHealthItem = (id: string, list: any[], setList: Function) => {
        setList(list.filter(item => item.id !== id));
    };

    // --- MODAL LOGIC ---
    const especiesDisponiveis = useMemo(() => PET_DATA.map((s) => ({ label: s.label, value: s.value })), []);
    
    const racasFiltradas = useMemo(() => {
        if (!especie) return [];
        const data = PET_DATA.find((s) => s.value === especie);
        return data ? data.breeds.filter((b) => b.label.toLowerCase().includes(busca.toLowerCase())) : [];
    }, [especie, busca]);

    const saudeData = useMemo(() => {
        if (!especie) return { vaccines: [], medications: [] };
        return HEALTH_DATA.find((s) => s.species_value === especie) || HEALTH_DATA.find((s) => s.species_value === 'outro');
    }, [especie]);

    const vacinasFiltradas = useMemo(() => saudeData?.vaccines.filter(v => v.name.toLowerCase().includes(busca.toLowerCase())) || [], [saudeData, busca]);
    const medicamentosFiltrados = useMemo(() => saudeData?.medications.filter(m => m.name.toLowerCase().includes(busca.toLowerCase())) || [], [saudeData, busca]);

    const modalData = useMemo(() => {
        switch (modalConfig?.type) {
            case 'especie': return especiesDisponiveis;
            case 'raca': return racasFiltradas;
            case 'vacina': return vacinasFiltradas;
            case 'medicamento': return medicamentosFiltrados;
            default: return [];
        }
    }, [modalConfig, especiesDisponiveis, racasFiltradas, vacinasFiltradas, medicamentosFiltrados]);

    const openModal = (type: 'especie' | 'raca' | 'vacina' | 'medicamento') => {
        const titles = { especie: 'Alterar Espécie', raca: 'Alterar Raça', vacina: 'Adicionar Vacina', medicamento: 'Adicionar Medicamento' };
        if ((type === 'raca' || type === 'vacina' || type === 'medicamento') && !especie) {
            Alert.alert('Atenção', 'Selecione uma espécie primeiro.');
            return;
        }
        setBusca('');
        setModalConfig({ type, title: titles[type] });
        setModalVisible(true);
    };

    const handleSelection = (item: any) => {
        if (modalConfig?.type === 'especie') {
            if (especie !== item.value) { setRaca(''); setHistoricoVacinas([]); setHistoricoMedicamentos([]); }
            setEspecie(item.value);
            setModalVisible(false);
        } else if (modalConfig?.type === 'raca') {
            setRaca(item.value);
            setModalVisible(false);
        } else {
            // Multi select logic for vaccines/meds
            const list = modalConfig?.type === 'vacina' ? historicoVacinas : historicoMedicamentos;
            const setList = modalConfig?.type === 'vacina' ? setHistoricoVacinas : setHistoricoMedicamentos;
            
            const jaExiste = list.find(i => i.id === item.id);
            if (jaExiste) {
                setList(list.filter(i => i.id !== item.id));
            } else {
                setList([...list, { id: item.id, name: item.name, data_aplicacao: '', data_desconhecida: false }]);
            }
        }
    };

    if (isLoading) {
        return <View style={styles.centered}><ActivityIndicator size="large" color="#1565C0" /></View>;
    }

    return (
        <View style={styles.background}>
            <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={{ flex: 1 }}>
                <View style={styles.header}>
                    <TouchableOpacity onPress={() => router.back()}><Ionicons name="arrow-back" size={28} color="white" /></TouchableOpacity>
                    <Text style={styles.headerTitle}>Editar {nome}</Text>
                    <View style={{ width: 28 }} />
                </View>

                <ScrollView contentContainerStyle={styles.scrollContainer} keyboardShouldPersistTaps="handled">
                    {/* NOME */}
                    <Text style={styles.inputLabel}>Nome do Pet</Text>
                    <TextInput style={styles.input} value={nome} onChangeText={setNome} />

                    {/* ESPECIE & RACA */}
                    <Text style={styles.inputLabel}>Espécie</Text>
                    <TouchableOpacity style={styles.pickerButton} onPress={() => openModal('especie')}>
                        <Text style={styles.pickerButtonText}>{especie ? especiesDisponiveis.find(e => e.value === especie)?.label : 'Selecione...'}</Text>
                        <Ionicons name="chevron-down" size={24} color="#ccc" />
                    </TouchableOpacity>

                    <Text style={styles.inputLabel}>Raça</Text>
                    <TouchableOpacity style={styles.pickerButton} onPress={() => openModal('raca')}>
                        <Text style={styles.pickerButtonText}>{raca ? PET_DATA.flatMap(s => s.breeds).find(r => r.value === raca)?.label : 'Selecione...'}</Text>
                        <Ionicons name="chevron-down" size={24} color="#ccc" />
                    </TouchableOpacity>

                    {/* DATA DE NASCIMENTO / IDADE */}
                    <View style={styles.sectionDivider} />
                    <View style={styles.labelComCheckbox}>
                        <Text style={styles.inputLabel}>Data de Nascimento</Text>
                        <TouchableOpacity style={styles.checkboxContainer} onPress={() => { setNaoSeiDataNascimento(!naoSeiDataNascimento); setDataNascimento(''); }}>
                            <Ionicons name={naoSeiDataNascimento ? 'checkbox' : 'square-outline'} size={24} color="#ccc" />
                            <Text style={styles.checkboxLabel}>Não sei</Text>
                        </TouchableOpacity>
                    </View>

                    <View style={[styles.inputIconContainer, naoSeiDataNascimento && styles.inputDisabled]}>
                        <TextInput
                            style={styles.inputComIcone}
                            placeholder="DD/MM/AAAA"
                            placeholderTextColor="#888"
                            value={dataNascimento}
                            onChangeText={handleDataNascimentoChange}
                            maxLength={10}
                            keyboardType="number-pad"
                            editable={!naoSeiDataNascimento}
                        />
                        <TouchableOpacity onPress={openDatePicker} disabled={naoSeiDataNascimento} style={{ padding: 10 }}>
                            <Ionicons name="calendar-outline" size={24} color={naoSeiDataNascimento ? '#555' : '#fff'} />
                        </TouchableOpacity>
                    </View>

                    {naoSeiDataNascimento && (
                        <View style={{ marginTop: 15 }}>
                            <Text style={styles.inputLabel}>Idade Aproximada</Text>
                            <View style={styles.idadeContainer}>
                                <TextInput style={styles.idadeInput} placeholder="Ex: 5" placeholderTextColor="#666" value={idadeAprox} onChangeText={setIdadeAprox} keyboardType="number-pad" />
                                <View style={styles.unidadeContainer}>
                                    <TouchableOpacity style={[styles.unidadeButton, unidadeIdade === 'meses' && styles.unidadeButtonSelected]} onPress={() => setUnidadeIdade('meses')}>
                                        <Text style={[styles.unidadeButtonText, unidadeIdade === 'meses' && styles.unidadeButtonTextSelected]}>Meses</Text>
                                    </TouchableOpacity>
                                    <TouchableOpacity style={[styles.unidadeButton, unidadeIdade === 'anos' && styles.unidadeButtonSelected]} onPress={() => setUnidadeIdade('anos')}>
                                        <Text style={[styles.unidadeButtonText, unidadeIdade === 'anos' && styles.unidadeButtonTextSelected]}>Anos</Text>
                                    </TouchableOpacity>
                                </View>
                            </View>
                        </View>
                    )}

                    {/* SEXO & PESO & COR */}
                    <View style={styles.rowContainer}>
                        <View style={{ flex: 1, marginRight: 10 }}>
                            <Text style={styles.inputLabel}>Sexo</Text>
                            <View style={styles.sexoContainer}>
                                <TouchableOpacity style={[styles.sexoButton, sexo === 'M' && styles.sexoButtonSelected]} onPress={() => setSexo('M')}>
                                    <Ionicons name="male" size={20} color={sexo === 'M' ? '#fff' : '#1565C0'} />
                                </TouchableOpacity>
                                <TouchableOpacity style={[styles.sexoButton, sexo === 'F' && styles.sexoButtonSelected]} onPress={() => setSexo('F')}>
                                    <Ionicons name="female" size={20} color={sexo === 'F' ? '#fff' : '#e91e63'} />
                                </TouchableOpacity>
                            </View>
                        </View>
                        <View style={{ flex: 1 }}>
                            <Text style={styles.inputLabel}>Cor</Text>
                            <TextInput style={styles.input} value={cor} onChangeText={setCor} placeholder="Ex: Preto" placeholderTextColor="#666" />
                        </View>
                    </View>

                    <View style={styles.labelComCheckbox}>
                        <Text style={styles.inputLabel}>Peso (kg)</Text>
                        <TouchableOpacity style={styles.checkboxContainer} onPress={() => { setNaoSeiPeso(!naoSeiPeso); setPeso(''); }}>
                            <Ionicons name={naoSeiPeso ? 'checkbox' : 'square-outline'} size={20} color="#ccc" />
                            <Text style={styles.checkboxLabel}>Não sei</Text>
                        </TouchableOpacity>
                    </View>
                    <TextInput style={[styles.input, naoSeiPeso && styles.inputDisabled]} value={peso} onChangeText={setPeso} keyboardType="numeric" editable={!naoSeiPeso} placeholder="Ex: 12.5" placeholderTextColor="#666" />

                    {/* SAÚDE - VACINAS */}
                    <View style={styles.sectionDivider} />
                    <Text style={styles.sectionTitle}>Carteira de Vacinação</Text>
                    <TouchableOpacity style={styles.addButton} onPress={() => openModal('vacina')}>
                        <Ionicons name="add-circle-outline" size={22} color="#fff" />
                        <Text style={styles.addButtonLabel}>Adicionar / Remover Vacinas</Text>
                    </TouchableOpacity>
                    {historicoVacinas.map((item) => (
                        <HistoricoItemCard
                            key={item.id}
                            item={item}
                            onDateChange={(text) => handleHealthDateChange(item.id, text, historicoVacinas, setHistoricoVacinas)}
                            onDateUnknownToggle={() => handleHealthUnknownToggle(item.id, historicoVacinas, setHistoricoVacinas)}
                            onRemove={() => handleRemoveHealthItem(item.id, historicoVacinas, setHistoricoVacinas)}
                        />
                    ))}

                    {/* SAÚDE - MEDICAMENTOS */}
                    <View style={{ marginTop: 20 }} />
                    <Text style={styles.sectionTitle}>Medicamentos Recentes</Text>
                    <TouchableOpacity style={styles.addButton} onPress={() => openModal('medicamento')}>
                        <Ionicons name="add-circle-outline" size={22} color="#fff" />
                        <Text style={styles.addButtonLabel}>Adicionar / Remover Medicamentos</Text>
                    </TouchableOpacity>
                    {historicoMedicamentos.map((item) => (
                        <HistoricoItemCard
                            key={item.id}
                            item={item}
                            onDateChange={(text) => handleHealthDateChange(item.id, text, historicoMedicamentos, setHistoricoMedicamentos)}
                            onDateUnknownToggle={() => handleHealthUnknownToggle(item.id, historicoMedicamentos, setHistoricoMedicamentos)}
                            onRemove={() => handleRemoveHealthItem(item.id, historicoMedicamentos, setHistoricoMedicamentos)}
                        />
                    ))}

                    {/* BOTÃO SALVAR */}
                    <TouchableOpacity style={styles.saveButton} onPress={handleSave} disabled={isSaving}>
                        {isSaving ? <ActivityIndicator color="#fff" /> : <Text style={styles.saveButtonText}>Salvar Alterações</Text>}
                    </TouchableOpacity>

                </ScrollView>
            </KeyboardAvoidingView>

            {/* Date Picker */}
            {showDatePicker && (
                <RNDateTimePicker
                    value={datePickerDate}
                    mode="date"
                    display="default"
                    onChange={onChangeDatePicker}
                    maximumDate={new Date()}
                />
            )}

            {/* Modal de Seleção */}
            <Modal animationType="slide" transparent={true} visible={modalVisible} onRequestClose={() => setModalVisible(false)}>
                <View style={styles.modalContainer}>
                    <View style={styles.modalContent}>
                        <Text style={styles.modalTitle}>{modalConfig?.title}</Text>
                        <TextInput style={styles.searchInput} placeholder="Pesquisar..." placeholderTextColor="#999" value={busca} onChangeText={setBusca} />
                        <FlatList
                            data={modalData as any[]}
                            keyExtractor={(item) => (item as any).id || (item as any).value}
                            renderItem={({ item }) => {
                                const isHealth = modalConfig?.type === 'vacina' || modalConfig?.type === 'medicamento';
                                const isSelected = isHealth && (
                                    modalConfig?.type === 'vacina'
                                        ? historicoVacinas.some(v => v.id === item.id)
                                        : historicoMedicamentos.some(m => m.id === item.id)
                                );
                                return (
                                    <TouchableOpacity style={styles.modalItem} onPress={() => handleSelection(item)}>
                                        <Text style={styles.modalItemText}>{(item as any).label || (item as any).name}</Text>
                                        {isHealth && <Ionicons name={isSelected ? 'checkbox' : 'square-outline'} size={24} color={isSelected ? '#28a745' : '#ccc'} />}
                                    </TouchableOpacity>
                                );
                            }}
                        />
                        <TouchableOpacity style={styles.modalCloseButton} onPress={() => setModalVisible(false)}>
                            <Text style={styles.modalCloseButtonText}>Confirmar / Fechar</Text>
                        </TouchableOpacity>
                    </View>
                </View>
            </Modal>
        </View>
    );
}

const styles = StyleSheet.create({
    background: { flex: 1, backgroundColor: '#1c1c1c' },
    header: { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 20, paddingTop: 50, paddingBottom: 15, backgroundColor: '#2c2c2c' },
    headerTitle: { flex: 1, textAlign: 'center', color: 'white', fontSize: 20, fontWeight: 'bold' },
    centered: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#1c1c1c' },
    scrollContainer: { padding: 20, paddingBottom: 50 },
    
    inputLabel: { color: '#fff', fontSize: 16, marginTop: 15, marginBottom: 8 },
    input: { backgroundColor: 'rgba(255,255,255,0.1)', padding: 12, borderRadius: 10, borderWidth: 1, borderColor: '#444', color: '#fff', fontSize: 16 },
    
    pickerButton: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', backgroundColor: 'rgba(255,255,255,0.1)', padding: 12, borderRadius: 10, borderWidth: 1, borderColor: '#444' },
    pickerButtonText: { color: '#fff', fontSize: 16 },

    inputIconContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: 'rgba(255,255,255,0.1)', borderRadius: 10, borderWidth: 1, borderColor: '#444' },
    inputComIcone: { flex: 1, padding: 12, color: '#fff', fontSize: 16 },
    inputDisabled: { backgroundColor: 'rgba(0,0,0,0.3)', borderColor: '#333', color: '#777' },

    labelComCheckbox: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 15, marginBottom: 5 },
    checkboxContainer: { flexDirection: 'row', alignItems: 'center' },
    checkboxLabel: { color: '#ccc', marginLeft: 8, fontSize: 14 },

    idadeContainer: { flexDirection: 'row', alignItems: 'center', backgroundColor: 'rgba(255,255,255,0.1)', borderRadius: 10, borderWidth: 1, borderColor: '#444', paddingLeft: 10 },
    idadeInput: { flex: 1, padding: 12, color: '#fff', fontSize: 16 },
    unidadeContainer: { flexDirection: 'row', margin: 4, backgroundColor: 'rgba(0,0,0,0.2)', borderRadius: 8 },
    unidadeButton: { paddingVertical: 8, paddingHorizontal: 12 },
    unidadeButtonSelected: { backgroundColor: '#1565C0', borderRadius: 6 },
    unidadeButtonText: { color: '#ccc', fontSize: 14 },
    unidadeButtonTextSelected: { color: '#fff', fontWeight: 'bold' },

    rowContainer: { flexDirection: 'row', marginTop: 5 },
    sexoContainer: { flexDirection: 'row', gap: 10 },
    sexoButton: { flex: 1, alignItems: 'center', justifyContent: 'center', padding: 12, borderRadius: 10, backgroundColor: 'rgba(255,255,255,0.1)', borderWidth: 1, borderColor: '#444' },
    sexoButtonSelected: { backgroundColor: '#1565C0', borderColor: '#1565C0' },

    sectionDivider: { height: 1, backgroundColor: '#444', marginVertical: 25 },
    sectionTitle: { color: '#1565C0', fontSize: 18, fontWeight: 'bold', marginBottom: 10 },
    
    addButton: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', backgroundColor: '#28a745', padding: 10, borderRadius: 8, marginBottom: 15 },
    addButtonLabel: { color: 'white', marginLeft: 8, fontWeight: 'bold' },

    saveButton: { backgroundColor: '#1565C0', padding: 15, borderRadius: 10, alignItems: 'center', marginTop: 30 },
    saveButtonText: { color: 'white', fontSize: 18, fontWeight: 'bold' },

    // Modal
    modalContainer: { flex: 1, justifyContent: 'flex-end', backgroundColor: 'rgba(0,0,0,0.6)' },
    modalContent: { backgroundColor: '#2c2c2c', borderTopLeftRadius: 20, borderTopRightRadius: 20, padding: 20, maxHeight: '80%' },
    modalTitle: { fontSize: 20, fontWeight: 'bold', color: '#fff', textAlign: 'center', marginBottom: 15 },
    searchInput: { backgroundColor: 'rgba(255,255,255,0.1)', padding: 10, borderRadius: 10, color: '#fff', marginBottom: 15, borderWidth: 1, borderColor: '#444' },
    modalItem: { paddingVertical: 15, borderBottomWidth: 1, borderBottomColor: '#444', flexDirection: 'row', justifyContent: 'space-between' },
    modalItemText: { color: '#fff', fontSize: 16 },
    modalCloseButton: { backgroundColor: '#1565C0', padding: 15, borderRadius: 10, marginTop: 20, alignItems: 'center' },
    modalCloseButtonText: { color: '#fff', fontWeight: 'bold' },
});