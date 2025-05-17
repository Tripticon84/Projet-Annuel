package esgi.pa.ui.nfc

import android.app.PendingIntent
import android.content.Intent
import android.content.IntentFilter
import android.nfc.NdefMessage
import android.nfc.NdefRecord
import android.nfc.NfcAdapter
import android.nfc.Tag
import android.nfc.tech.Ndef
import android.os.Bundle
import android.provider.Settings
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import esgi.pa.databinding.FragmentNfcWriterBinding
import esgi.pa.util.SessionManager
import org.json.JSONObject
import java.nio.charset.Charset

class NfcWriterFragment : Fragment() {
    private val TAG = "NfcWriterFragment"
    private var _binding: FragmentNfcWriterBinding? = null
    private val binding get() = _binding!!

    private var nfcAdapter: NfcAdapter? = null
    private lateinit var sessionManager: SessionManager

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentNfcWriterBinding.inflate(inflater, container, false)

        // Initialisation de l'adaptateur NFC
        nfcAdapter = NfcAdapter.getDefaultAdapter(requireContext())

        // Initialisation du SessionManager
        sessionManager = SessionManager(requireContext())

        // Configuration de l'interface utilisateur
        setupUI()

        return binding.root
    }

    private fun setupUI() {
        // Afficher les détails de l'utilisateur
        val userId = sessionManager.getCollaborateurId()
        val username = sessionManager.getUsername() ?: "Non défini"

        binding.tvTitle.text = "Programmation carte d'accès"
        binding.tvEventDetails.text = "Utilisateur: $username\nID: $userId"

        binding.btnWrite.setOnClickListener {
            checkNfcStatus()
        }

        checkNfcStatus() // Vérifie l'état du NFC au démarrage
    }

    private fun checkNfcStatus() {
        when {
            nfcAdapter == null -> {
                binding.tvStatus.text = "Cet appareil ne supporte pas la technologie NFC"
                binding.btnWrite.isEnabled = false
            }
            !nfcAdapter!!.isEnabled -> {
                binding.tvStatus.text = "NFC est désactivé. Activez-le dans les paramètres"
                binding.btnWrite.isEnabled = true
                binding.btnWrite.text = "Activer NFC"
                binding.btnWrite.setOnClickListener {
                    startActivity(Intent(Settings.ACTION_NFC_SETTINGS))
                }
            }
            else -> {
                binding.tvStatus.text = "Prêt pour l'écriture. Approchez une carte NFC"
                binding.btnWrite.text = "Écrire sur la carte"
                binding.btnWrite.isEnabled = true
                binding.btnWrite.setOnClickListener {
                    binding.tvStatus.text = "Approchez la carte NFC du téléphone..."
                }
                enableNfcWriteMode()
            }
        }
    }

    private fun enableNfcWriteMode() {
        try {
            val intent = Intent(requireContext(), requireActivity().javaClass).apply {
                addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP)
            }

            val pendingIntent = PendingIntent.getActivity(
                requireContext(), 0, intent, PendingIntent.FLAG_MUTABLE
            )

            val filters = arrayOf(IntentFilter(NfcAdapter.ACTION_TAG_DISCOVERED))

            nfcAdapter?.enableForegroundDispatch(
                requireActivity(), pendingIntent, filters, null
            )
        } catch (e: Exception) {
            Log.e(TAG, "Erreur lors de l'activation du mode écriture NFC: ${e.message}", e)
        }
    }

    fun writeToTag(intent: Intent) {
        val tag = intent.getParcelableExtra<Tag>(NfcAdapter.EXTRA_TAG) ?: return

        // Récupération des données utilisateur depuis SessionManager
        val userId = sessionManager.getUserId()
        val username = sessionManager.getUsername()
        val collaborateurId = sessionManager.getCollaborateurId()

        // On ne stocke jamais le token ou mot de passe sur la carte pour des raisons de sécurité

        // Création des données à écrire (JSON)
        val userData = JSONObject().apply {
            put("userId", userId)
            put("username", username)
            put("collaborateurId", collaborateurId)
            put("type", "user_access")
        }.toString()

        val ndef = Ndef.get(tag) ?: return

        try {
            ndef.connect()

            if (!ndef.isWritable) {
                showResult("La carte n'est pas inscriptible", isSuccess = false)
                return
            }

            val maxSize = ndef.maxSize
            if (userData.toByteArray().size > maxSize) {
                showResult("Les données dépassent la capacité de la carte", isSuccess = false)
                return
            }

            // Création de l'enregistrement NDEF
            val langBytes = "fr".toByteArray(Charset.forName("US-ASCII"))
            val textBytes = userData.toByteArray(Charset.forName("UTF-8"))

            val recordPayload = ByteArray(1 + langBytes.size + textBytes.size)
            recordPayload[0] = langBytes.size.toByte()
            System.arraycopy(langBytes, 0, recordPayload, 1, langBytes.size)
            System.arraycopy(textBytes, 0, recordPayload, 1 + langBytes.size, textBytes.size)

            val record = NdefRecord(NdefRecord.TNF_WELL_KNOWN, NdefRecord.RTD_TEXT,
                ByteArray(0), recordPayload)
            val message = NdefMessage(arrayOf(record))

            // Écriture du message sur la carte
            ndef.writeNdefMessage(message)

            showResult("Carte d'accès programmée avec succès !", isSuccess = true)

        } catch (e: Exception) {
            Log.e(TAG, "Erreur lors de l'écriture sur la carte NFC: ${e.message}", e)
            showResult("Erreur: ${e.message}", isSuccess = false)
        } finally {
            try {
                ndef.close()
            } catch (e: Exception) {
                Log.e(TAG, "Erreur lors de la fermeture de la connexion NFC: ${e.message}", e)
            }
        }
    }

    private fun showResult(message: String, isSuccess: Boolean) {
        requireActivity().runOnUiThread {
            binding.tvStatus.text = message
            Toast.makeText(requireContext(), message, Toast.LENGTH_LONG).show()
        }
    }

    override fun onResume() {
        super.onResume()
        nfcAdapter?.let {
            if (it.isEnabled) {
                enableNfcWriteMode()
            } else {
                checkNfcStatus()
            }
        }
    }

    override fun onPause() {
        super.onPause()
        nfcAdapter?.disableForegroundDispatch(requireActivity())
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}