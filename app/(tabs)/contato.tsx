  import { useState } from "react";
  import {
    StyleSheet,
    Text,
    View,
    TouchableOpacity,
    TextInput,
    Linking,
    ScrollView,
    Image,
  } from "react-native";
  import { Ionicons } from "@expo/vector-icons";
  import { useTheme } from "@/app/context/ThemeContext";
  import { lightColors, darkColors, darkColorsContat, lightColorsContat } from "@/app/color/themeConfig";
  // import QRCode from "react-native-qrcode-svg";

  export default function ContatoScreen() {
    const { theme } = useTheme();
    const colors = theme === "dark" ? darkColorsContat : lightColorsContat;

    const [nome, setNome] = useState("");
    const [mensagem, setMensagem] = useState("");

    // Funções de ação
    const abrirWhatsApp = () => {
      Linking.openURL(
        "https://wa.me/5516999999999?text=Olá! Gostaria de falar com a clínica veterinária 🐾"
      );
    };

    const ligar = () => {
      Linking.openURL("tel:+5516999999999");
    };

    const enviarEmail = () => {
      Linking.openURL(
        "mailto:contato@clinicavetPetto.com?subject=Contato%20via%20app"
      );
    };

    const abrirMapa = () => {
      const url = "https://www.google.com/maps/search/?api=1&query=clinicas+veterinarias+em+Taquaritinga+SP";
      Linking.openURL(url);
    };  

    const emergencia = () => {
      Linking.openURL("tel:+5516999111111");
    };

    return (
      <ScrollView
        contentContainerStyle={[styles.container, { backgroundColor: colors.background }]}
      >
        {/* Cabeçalho com veterinário */}
        <View style={styles.header}>
          <Image
            source={{ uri: "https://cdn-icons-png.flaticon.com/512/616/616408.png" }}
            style={styles.avatar}
          />
          <Text style={[styles.nomeVet, { color: colors.text }]}>
            Dra. Camila Andrade
          </Text>
          <Text style={[styles.status, { color: colors.subtitle }]}>
            🟢 Disponível para atendimento
          </Text>
        </View>

        {/* Botões de contato */}
        <View style={styles.contactSection}>
          <TouchableOpacity style={[styles.button, { backgroundColor: colors.corFundoInput }]} onPress={abrirWhatsApp}>
            <Ionicons name="logo-whatsapp" size={24} color="#25D366" />
            <Text style={[styles.buttonText, { color: colors.textAlert }]}>
              Falar no WhatsApp
            </Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, { backgroundColor: colors.corFundoInput }]} onPress={ligar}>
            <Ionicons name="call-outline" size={24} color="#4A90E2" />
            <Text style={[styles.buttonText, { color: colors.textAlert }]}>
              Ligar agora
            </Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, { backgroundColor: colors.corFundoInput }]} onPress={enviarEmail}>
            <Ionicons name="mail-outline" size={24} color="#FF8C00" />
            <Text style={[styles.buttonText, { color: colors.textAlert }]}>
              Enviar e-mail
            </Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.button, { backgroundColor: colors.corFundoInput }]} onPress={abrirMapa}>
            <Ionicons name="location-outline" size={24} color="#E74C3C" />
            <Text style={[styles.buttonText, { color: colors.textAlert }]}>
              Ver localização
            </Text>
          </TouchableOpacity>

          {/* 🚨 Botão de emergência */}
          <TouchableOpacity style={[styles.emergencyButton, { backgroundColor: "#FF4D4D" }]} onPress={emergencia}>
            <Ionicons name="alert-circle-outline" size={24} color="#fff" />
            <Text style={styles.emergencyText}>Emergência 24h</Text>
          </TouchableOpacity>
        </View>

        {/* Formulário de mensagem */}
        <View style={[styles.form, { backgroundColor: colors.corFundoInput }]}>
          <Text style={[styles.formTitle, { color: colors.text }]}>
            📩 Enviar mensagem direta
          </Text>

          <TextInput
            style={[styles.input, { backgroundColor: colors.background, color: colors.text }]}
            placeholder="Seu nome"
            placeholderTextColor={colors.subtitle}
            value={nome}
            onChangeText={setNome}
          />

          <TextInput
            style={[
              styles.input,
              styles.textArea,
              { backgroundColor: colors.background, color: colors.text },
            ]}
            placeholder="Escreva sua mensagem..."
            placeholderTextColor={colors.subtitle}
            value={mensagem}
            onChangeText={setMensagem}
            multiline
          />

          <TouchableOpacity
            style={[styles.submitButton, { backgroundColor: colors.button }]}
            onPress={() => alert("Mensagem enviada com sucesso!")}
          >
            <Text style={[styles.submitText, { color: "#fff" }]}>Enviar</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.qrContainer}>
          <Text style={[styles.qrTitle, { color: colors.text }]}>
            ☑️ Salve o contato da clínica ☑️
          </Text>
          <Text style={[styles.qrTitle, { color: colors.text }]}>
            👇Escaneie o QR code abaixo👇
          </Text>
          <Image
            source={{
              uri: `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(
                "https://wa.me/5516999999999?text=Olá! Gostaria de agendar uma consulta 🐶"
              )}`,
            }}
            style={{ width: 160, height: 160, marginTop: 10 }}
          />
        </View>
      </ScrollView>
    );
  }

  const styles = StyleSheet.create({
    container: {
      alignItems: "center",
      padding: 20,
    },
    header: {
      alignItems: "center",
      marginBottom: 20,
    },
    avatar: {
      width: 100,
      height: 100,
      borderRadius: 50,
      marginBottom: 10,
    },
    nomeVet: {
      fontSize: 20,
      fontWeight: "bold",
    },
    status: {
      fontSize: 14,
      marginTop: 2,
    },
    contactSection: {
      width: "100%",
      marginBottom: 25,
    },
    button: {
      flexDirection: "row",
      alignItems: "center",
      borderRadius: 10,
      padding: 12,
      marginVertical: 6,
      elevation: 2,
    },
    buttonText: {
      fontSize: 16,
      marginLeft: 10,
    },
    emergencyButton: {
      flexDirection: "row",
      alignItems: "center",
      borderRadius: 10,
      padding: 14,
      justifyContent: "center",
      marginTop: 10,
      elevation: 3,
    },
    emergencyText: {
      color: "#fff",
      fontSize: 18,
      fontWeight: "bold",
      marginLeft: 8,
    },
    form: {
      width: "100%",
      borderRadius: 12,
      padding: 15,
      elevation: 2,
      marginBottom: 25,
    },
    formTitle: {
      fontSize: 16,
      fontWeight: "bold",
      marginBottom: 10,
    },
    input: {
      borderRadius: 8,
      padding: 10,
      marginBottom: 10,
    },
    textArea: {
      height: 100,
      textAlignVertical: "top",
    },
    submitButton: {
      borderRadius: 8,
      paddingVertical: 10,
      alignItems: "center",
    },
    submitText: {
      fontWeight: "bold",
      fontSize: 16,
    },
    qrContainer: {
      alignItems: "center",
      marginBottom: 40,
    },
    qrTitle: {
      fontSize: 16,
      fontWeight: "600",
      marginBottom: 10,
    },
  });
