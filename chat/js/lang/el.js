/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author panas
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Ajax Chat language Object:
var ajaxChatLang = {

  login: '%s μπήκε στο Chat.',
  logout: '%s βγήκε από το Chat.',
  logoutTimeout: '%s βγήκε από το Chat (Ανενεργό).',
  logoutIP: '%s βγήκε από το Chat (Λανθασμένη IP).',
  logoutKicked: '%s βγήκε από το Chat (Kicked).',
  channelEnter: '%s μπήκε στο κανάλι.',
  channelLeave: '%s βγήκε από το κανάλι.',
  privmsg: '(ψιθυρίζει)',
  privmsgto: '( ψιθυρίζει σε %s)',
  invite: '%s σας καλεί να συμμετάσχετε στο %s.',
  inviteto: 'Η πρόσκληση σας σε %s να συμμετάσχει στο κανάλι %s έχει σταλεί.',
  uninvite: '%s τερματίζει την πρόσκληση σας για το κανάλι %s.',
  uninviteto: 'Η πρόσκληση σας σε %s για το κανάλι %s έχει σταλεί.',
  queryOpen: 'Άνοιξε πρίβε κανάλι σε %s.',
  queryClose: ' Το πρίβε κανάλι %s έκλεισε.',
  ignoreAdded: '%s προστέθηκε στη λίστα αγνόησης.',
  ignoreRemoved: ' %s αφαιρέθηκε  από τη λίστα αγνόησης .',
  ignoreList: 'Αγνοήμενοι χρήστες:',
  ignoreListEmpty: 'Δεν υπάρχουν αγνοημένοι χρήστες.',
  who: 'Χρήστες παρόν:',
  whoChannel: 'Χρήστες συνδεδεμένοι στο κανάλι %s:',
  whoEmpty: 'Δεν υπάρχουν χρήστες στο συγκεκριμένο κανάλι.',
  list: 'Διαθέσιμα κανάλια:',
  bans: 'Αποκλεισμένοι χρήστες:',
  bansEmpty: 'Δεν υπάρχουν αποκλεισμένοι χρήστες.',
  unban: 'Ο αποκλεισμός %s αφαιρέθηκε.',
  whois: ' %s - IP διεύθυνση:',
  whereis: 'Χρήστης %s είναι στο κανάλι %s.',
  roll: '%s ρίχνει %s και φέρνει %s.',
  nick: '%s άλλαξε το όνομα σε %s.',
  toggleUserMenu: 'Αλλαγή μενού χρήστη για %s',
  userMenuLogout: 'Αποσύνδεση',
  userMenuWho: 'Εμφάνιση λίστας συνδεδεμένων',
  userMenuList: 'Εμφάνιση λίστας διαθέσιμων καναλιών',
  userMenuAction: 'Περιγραφή ενέργειας',
  userMenuRoll: 'Ρίξιμο ζαριών',
  userMenuNick: 'Αλλαγή ονόματος',
  userMenuEnterPrivateRoom: 'Εισαγωγή σε πριβέ δωμάτιο',
  userMenuSendPrivateMessage: 'Αποστολή προσωπικού μηνύματος',
  userMenuDescribe: 'Αποστολή προσωπικής ενέργειας',
  userMenuOpenPrivateChannel: 'Άνοιγμα πριβέ καναλιού',
  userMenuClosePrivateChannel: 'Κλείσιμο πριβέ καναλιού',
  userMenuInvite: 'Πρόσκληση',
  userMenuUninvite: 'Ακύρωση πρόσκλησης',
  userMenuIgnore: 'Αγνόηση/Αποδοχή',
  userMenuIgnoreList: 'Εμφάνιση λίστας αγνοημένων',
  userMenuWhereis: 'Εμφάνιση καναλιού',
  userMenuKick: 'Kick/Ban',
  userMenuBans: 'Εμφάνιση λίστας αποκλεισμένων',
  userMenuWhois: 'Εμφάνιση IP',
  unbanUser: 'Επαναφορά αποκλεισμού για %s',
  joinChannel: 'Μπαίνει στο κανάλι %s',
  cite: '%s είπε:',
  urlDialog: 'παρακαλούμε εισάγετε την διεύθυνση (URL) της ιστοσελίδας:',
  deleteMessage: 'Διαγραφή αυτού του μηνύματος',
  deleteMessageConfirm: 'Θέλετε να διαγράψετε το επιλεγμένο μήνυμα?',
  errorCookiesRequired: 'Τα cookies είναι απαραίτητα για το chat.',
  errorUserNameNotFound: 'Σφάλμα: Ο χρήστης %s δεν βρέθηκε.',
  errorMissingText: 'Σφάλμα: Λείπει το μήνυμα.',
  errorMissingUserName: ': Λείπει ο χρήστης.',
  errorInvalidUserName: 'Error: Invalid username.',
  errorUserNameInUse: 'Error: Username already in use.',
  errorMissingChannelName: 'Σφάλμα: Λείπει το όνομα του καναλιού.',
  errorInvalidChannelName: 'Σφάλμα: Ακατάλληλο όνομα καναλιού: %s',
  errorPrivateMessageNotAllowed: 'Σφάλμα: Τα προσωπικά μηνύματα δεν επιτρέπονται.',
  errorInviteNotAllowed: 'Σφάλμα: Δεν σας επιτρέπετε να καλέσετε άλλούς στο κανάλι.',
  errorUninviteNotAllowed: 'Σφάλμα: Δεν σας επιτρέπετε να τερματίσετε την πρόσκληση άλλων από το κανάλι.',
  errorNoOpenQuery: ': Δεν ανοίχθηκε πρίβε κανάλι.',
  errorKickNotAllowed: 'Δεν σας επιτρέπετε να πετάξετε %s.',
  errorCommandNotAllowed: 'Σφάλμα: Δεν επιτρέπετε η εντολή: %s',
  errorUnknownCommand: 'Σφάλμα: Άγνωστη εντολή: %s',
  errorMaxMessageRate: 'Σφάλμα: Υπερβήκατε τον μέγιστο αριθμό μηνυμάτων ανά λεπτό.',
  errorConnectionTimeout: 'Σφάλμα: Έληξε ο χρόνος σύνδεσης. Προσπαθήστε ξανά.',
  errorConnectionStatus: 'Σφάλμα: Κατάσταση σύνδεσης: %s',
  errorSoundIO: 'Σφάλμα: Απέτυχε η φόρτωση του αρχείου ήχου (Flash IO Σφάλμα).',
  errorSocketIO: 'Σφάλμα: Η σύνδεση στο socket του διακομιστή απέτυχε (Flash IO Σφάλμα).',
  errorSocketSecurity: 'Σφάλμα:Η σύνδεση στο socket του διακομιστή απέτυχε (Σφάλμα ασφαλείας του Flash ).',
  errorDOMSyntax: 'Σφάλμα: Άκυρη DOM σύνταξη (DOM ID: %s).'

}