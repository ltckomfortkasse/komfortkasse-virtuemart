# Komfortkasse-Plugin für VirtueMart

Das Komfortkasse-Plugin für VirtueMart (die Shop-Komponente für Joomla!) automatisiert den Zahlungsabgleich für **Vorkasse, Rechnung und Nachnahme**. Komfortkasse ordnet eingehende Überweisungen automatisch den offenen Bestellungen Ihres Shops zu und setzt den Bestellstatus, sobald das Geld da ist. Das Plugin verbindet den VirtueMart-Shop mit Komfortkasse; den Abgleich mit dem Bankkonto übernimmt Komfortkasse. Das Plugin läuft ab VirtueMart 3.0. Technisch ist es ein VirtueMart-Zahlungsplugin (Joomla-Plugin der Gruppe `vmpayment`), im Checkout fügt es aber keine Zahlungsart hinzu.

[Komfortkasse für VirtueMart](https://komfortkasse.eu/shopsysteme/virtuemart/) · [Kostenlos registrieren](https://ssl.komfortkasse.eu/public/register.jsf) · [Installationsanleitung](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161598) · [Hilfe & Dokumentation](https://komfortkasse.freshdesk.com/support/solutions/14000075022)

## Eckdaten

| Merkmal | Angabe |
|---|---|
| Shopsystem | [VirtueMart](https://virtuemart.net/) (früher mambo-phpShop), die Shop-Komponente für das Content-Management-System [Joomla!](https://www.joomla.org/) |
| Unterstützte Versionen | VirtueMart ab 3.0 |
| Zahlungsarten | Vorkasse (Vorauskasse, Banküberweisung), Rechnung (Kauf auf Rechnung), Nachnahme |
| Aufgabe des Plugins | Überträgt offene Bestellungen an Komfortkasse und setzt den Bestellstatus bei Zahlungseingang oder Storno |
| Plugin-Typ | Joomla-Plugin der Gruppe `vmpayment` (VirtueMart-Zahlungsplugin); seine Einstellungen stehen in einer unveröffentlichten Zahlungsart „Komfortkasse“ |
| Für wen | Betreiber von VirtueMart-Shops und Agenturen, die solche Shops betreuen |
| Kosten | Das Plugin ist kostenlos. Für Komfortkasse gelten die [Preise](https://komfortkasse.eu/preise/) auf komfortkasse.eu. Auch im [Erweiterungsverzeichnis von VirtueMart](https://extensions.virtuemart.net/payments/komfortkasse-eu-detail) ist das Plugin kostenlos gelistet. |
| Anbieter | LTC Information Services GmbH, [komfortkasse.eu](https://komfortkasse.eu/) |

## Was ist Komfortkasse?

Komfortkasse ist ein Online-Dienst der LTC Information Services GmbH, der Zahlungen per Banküberweisung für Onlineshops automatisiert. Komfortkasse ruft die Zahlungseingänge vom Bankkonto ab, ordnet sie den offenen Bestellungen zu und meldet den Zahlungsstatus an den Shop zurück. Der Dienst richtet sich an Online-Händler in Deutschland, Österreich und der Schweiz.

Komfortkasse unterstützt die Zahlungsarten, bei denen das Geld per Überweisung kommt:

- [Vorkasse](https://komfortkasse.eu/loesungen/vorkasse/): Der Käufer überweist vor dem Versand. Komfortkasse prüft den Zahlungseingang und setzt den Bestellstatus im Shop automatisch.
- [Rechnung](https://komfortkasse.eu/loesungen/rechnung/): Die Ware wird sofort geliefert, der Käufer überweist danach. Komfortkasse überwacht die Zahlung und sendet bei Bedarf Zahlungserinnerungen und Mahnungen.
- [Nachnahme](https://komfortkasse.eu/loesungen/nachnahme/): Der Versanddienstleister kassiert bei der Zustellung und überweist den Betrag. Komfortkasse überwacht diese Überweisung.

### So funktioniert Komfortkasse mit VirtueMart

1. Das Komfortkasse-Plugin überträgt die offenen Bestellungen aus VirtueMart an Ihren Komfortkasse-Account.
2. Komfortkasse ist mit Ihrem Bankkonto verbunden ([unterstützte Banken](https://komfortkasse.eu/banken/)) und ordnet eingehende Zahlungen den Bestellungen zu.
3. Das Plugin setzt die Bestellung in VirtueMart auf den gewählten Status, z. B. „Zahlung eingegangen“, oder auf „storniert“, wenn keine Zahlung kommt.

Komfortkasse versendet außerdem Zahlungserinnerungen an Käufer, behandelt Teil- und Überzahlungen und wickelt Rückerstattungen ab. Alle Funktionen sind auf [komfortkasse.eu](https://komfortkasse.eu/) beschrieben.

## Funktionen des Komfortkasse-Plugins für VirtueMart

- **Bestellungen übertragen:** Das Plugin exportiert offene Bestellungen nach Zahlungsart (Vorkasse, Rechnung, Nachnahme) und Bestellstatus. Als offen gilt eine Bestellung, deren Zahlungsart in einer der drei Einstellungen für Vorkasse, Rechnung oder Nachnahme ausgewählt ist und deren Bestellstatus zu den dort gewählten offenen Status gehört, unabhängig vom Bestelldatum. Je Bestellung übergibt das Plugin unter anderem Bestellnummer, Bestelldatum, Gesamtbetrag, Währung und Wechselkurs, Zahlungsart, Kundennummer, E-Mail-Adresse, Sprache, Rechnungs- und Lieferadresse sowie die Artikelnummern (ersatzweise die Artikelnamen).
- **Bestellstatus setzen:** Bei Zahlungseingang und bei Storno bzw. ausbleibender Zahlung setzt das Plugin die Bestellung auf den Status, den Sie je Zahlungsart gewählt haben, und schreibt einen Eintrag mit dem Kommentar „Komfortkasse ID …“ in die Bestellhistorie; der Käufer wird dabei nicht benachrichtigt. Bestellungen, die nicht mehr in einem der offenen Status stehen, etwa weil sie von Hand bearbeitet wurden, lässt das Plugin unverändert. Den Status schreibt das Plugin direkt in die Bestellung (Tabelle `#__virtuemart_orders`); was VirtueMart bei einer Statusänderung im Admin-Bereich zusätzlich erledigt, etwa die Status-E-Mail an den Käufer oder Buchungen im Lagerbestand, löst es dabei nicht aus.
- **Vorhandene Zahlungsarten nutzen:** Das Plugin arbeitet mit den Zahlungsarten des Shops; in den Einstellungen wählen Sie sie je Zahlungsart aus einer Liste der VirtueMart-Zahlungsarten, die Name und ID zeigt. Es fügt im Checkout keine eigene Zahlungsart hinzu.
- **Einstellungen in einer Zahlungsart:** VirtueMart speichert die Einstellungen von Zahlungsplugins in einer Zahlungsart. Für das Komfortkasse-Plugin legen Sie deshalb einmalig eine Zahlungsart „Komfortkasse“ an, die unveröffentlicht bleibt und Käufern nicht angezeigt wird. Aus ihr liest das Plugin seine Einstellungen und die Verbindungsdaten (Tabelle `#__virtuemart_paymentmethods`).
- **Echtzeit-Übertragung:** Komfortkasse liest neue Bestellungen regelmäßig aus dem Shop aus. Zusätzlich meldet das Plugin jede neue Bestellung sofort beim Bestellabschluss (VirtueMart-Ereignis `plgVmConfirmedOrder`), wenn ihre Zahlungsart in den Einstellungen steht und sie einen der offenen Status hat, sodass der Käufer die Zahlungsinformationen ohne Wartezeit erhält. Code-Ergänzungen im Shop sind dafür nicht nötig. Das Plugin sendet die Meldung ausschließlich per HTTPS an `ssl.komfortkasse.eu` und prüft dabei das Zertifikat. Schlägt die Meldung fehl oder steht HTTPS nicht zur Verfügung, übernimmt Komfortkasse die Bestellung beim nächsten regelmäßigen Abruf.
- **Verschlüsselte Übertragung:** Das Plugin überträgt die Daten verschlüsselt, bevorzugt mit OpenSSL. Die Schlüssel setzt Komfortkasse beim Verbinden des Shops automatisch.
- **Geschützter Aufruf:** Komfortkasse ruft das Plugin über `index.php?option=com_virtuemart&view=cart&type=vmpayment&name=komfortkasse&action=<Aktion>` auf. Das Plugin reagiert nur auf Aufrufe mit `name=komfortkasse` und einer der Aktionen `init`, `test`, `readorders`, `updateorders` und `info`; Aufrufe anderer Erweiterungen, die ebenfalls einen Parameter `action` verwenden, lässt es unberührt. Bestelldaten gibt das Plugin nur gegen den Zugriffscode heraus; auch den Bestellstatus ändert es nur mit diesem Code. Den Zugriffscode speichert das Plugin nur als MD5-Hash, und einen bereits gesetzten Code überschreibt es beim Verbinden nicht.

## Voraussetzungen

- Joomla! mit VirtueMart ab 3.0
- Ein Komfortkasse-Account ([kostenlos registrieren](https://ssl.komfortkasse.eu/public/register.jsf))
- Ein Bankkonto, das Sie mit Komfortkasse verbinden ([unterstützte Banken](https://komfortkasse.eu/banken/)), oder ein integriertes Bankkonto von Komfortkasse
- Zahlungsarten in VirtueMart für die Zahlungsarten, die Komfortkasse übernehmen soll, z. B. Vorkasse per Überweisung, Rechnung oder Nachnahme
- Empfohlen: PHP mit OpenSSL-Erweiterung, für die Verschlüsselung und für die Echtzeit-Übertragung per HTTPS. Ohne OpenSSL (und ohne die veraltete mcrypt-Erweiterung) überträgt das Plugin die Daten nur Base64-kodiert; das ist keine Verschlüsselung.
- Für die Echtzeit-Übertragung zusätzlich: `allow_url_fopen` in der PHP-Konfiguration aktiv
- Ein Zugang zum Joomla-Administrator, mit dem Sie Erweiterungen installieren und VirtueMart-Zahlungsarten anlegen können

## Installation in VirtueMart

Die Installation besteht aus fünf Schritten: Plugin herunterladen, in Joomla installieren, die Zahlungsart „Komfortkasse“ anlegen, die Einstellungen wählen und den Shop mit dem Komfortkasse-Account verbinden.

1. **Plugin herunterladen:** Auf der Seite [Komfortkasse für VirtueMart](https://komfortkasse.eu/shopsysteme/virtuemart/) über „Aktuelle Version herunterladen“ die ZIP-Datei laden. Sie enthält den Ordner `komfortkasse` mit dem Installationsmanifest `komfortkasse.xml`; den Quellcode des Plugins enthält dieses Repository im selben Ordner. Komfortkasse ist außerdem im [Erweiterungsverzeichnis von VirtueMart](https://extensions.virtuemart.net/payments/komfortkasse-eu-detail) gelistet; der Eintrag dort kann eine ältere Version enthalten.
2. **In Joomla installieren:** Im Joomla-Administrator den „Extension Manager“ (Erweiterungsverwaltung) öffnen, die ZIP-Datei auswählen und auf „Upload & Install“ klicken. Joomla meldet die erfolgreiche Installation und legt das Plugin im Ordner `plugins/vmpayment/komfortkasse` ab.
3. **Zahlungsart „Komfortkasse“ anlegen:** Unter „VirtueMart“ → „Payment Methods“ auf „New“ klicken. Als „Zahlungsname“ und als „Zahlungsmethode“ jeweils „Komfortkasse“ eintragen bzw. wählen, „Published“ auf „No“ setzen und die übrigen Felder leer lassen. Legen Sie nur eine solche Zahlungsart an, denn das Plugin liest seine Einstellungen nur aus einer Zahlungsart dieses Typs. Unter dem Namen „Komfortkasse“ blendet das Plugin sie in seinen eigenen Auswahllisten aus.
4. **Einstellungen wählen:** Im Reiter „Configuration“ der neuen Zahlungsart „Export Bestellungen“ und „Update Bestellungen“ („Export orders“, „Update orders“) auf „ja“ setzen. Für Vorkasse, Rechnung und Nachnahme jeweils wählen: die Zahlungsarten des Shops (keine Auswahl, wenn Komfortkasse eine Zahlungsart nicht übernehmen soll), die Bestellstatus offener Bestellungen (bei Nachnahme: versendeter Bestellungen; mehrere möglich), den Status bei Zahlungseingang und den Status bei Storno bzw. ausbleibender Zahlung. Bei Vorkasse gehört laut Anleitung immer „Pending“ (`P`) zu den offenen Status; sonst scheitert die sofortige Übertragung neuer Bestellungen, und der Käufer erhält die Zahlungsinformationen unter Umständen später. Danach die Zahlungsart speichern.
5. **Shop verbinden:** Den Shop in Ihrem Komfortkasse-Account verbinden ([Erste Schritte: Einrichtung und Verbindung zum Shop](https://komfortkasse.freshdesk.com/support/solutions/articles/14000114082)). Verschlüsselung, Zugriffscode, API-Schlüssel sowie öffentlicher und privater Schlüssel werden dabei automatisch gesetzt und dürfen nicht geändert werden.

In einer neu angelegten Zahlungsart „Komfortkasse“ stehen diese Standardwerte (Codes der Standard-Bestellstatus von VirtueMart, in Klammern der englische Name):

| Zahlungsart | Zahlungsarten (Vorauswahl) | Status offen bzw. versendet | Status Zahlung erhalten | Status storniert bzw. keine Zahlung |
|---|---|---|---|---|
| Vorkasse | Zahlungsart mit der ID `2` | `P` (Pending) | `C` (Confirmed) | `X` (Cancelled) |
| Rechnung | Zahlungsart mit der ID `3` | `U` (Confirmed by shopper) | `C` (Confirmed) | `D` (Denied) |
| Nachnahme | Zahlungsart mit der ID `1` | `S` (Shipped) | `C` (Confirmed) | `X` (Cancelled) |

„Export Bestellungen“ und „Update Bestellungen“ stehen ab Werk auf „ja“. Die Vorauswahl bei den Zahlungsarten richtet sich nach der ID der VirtueMart-Zahlungsart (`virtuemart_paymentmethod_id`); welche Zahlungsarten das sind, hängt vom Shop ab. Prüfen Sie die Auswahl daher. Das Plugin vergleicht die gewählten IDs mit der Zahlungsart, die VirtueMart in der Bestellung speichert.

Ausführliche Anleitung mit Screenshots: [Installationsanleitung VirtueMart](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161598)

## Update

Für ein Update installieren Sie die neue Version wie bei der Installation über den „Extension Manager“ von Joomla; das Manifest des Plugins erlaubt die Installation über eine bestehende Version (`method="upgrade"`). Die Einstellungen und die Verbindung zu Komfortkasse bleiben dabei erhalten, denn sie stehen in der Zahlungsart „Komfortkasse“ in der Datenbank (Tabelle `#__virtuemart_paymentmethods`) und nicht in den Dateien des Plugins.

1. Die Datenbank und den Ordner `plugins/vmpayment/komfortkasse` sichern.
2. Die neue Version auf der Seite [Komfortkasse für VirtueMart](https://komfortkasse.eu/shopsysteme/virtuemart/) herunterladen.
3. Die ZIP-Datei im „Extension Manager“ mit „Upload & Install“ installieren. Die Zahlungsart „Komfortkasse“ legen Sie dabei nicht neu an.

## Häufige Fragen

### Was kostet das Komfortkasse-Plugin für VirtueMart?

Das Plugin ist kostenlos. Für Komfortkasse selbst fällt eine prozentuale Gebühr pro Zahlungseingang an, ohne Grundgebühr und ohne Einrichtungsgebühr. Die aktuellen Sätze stehen auf der [Preisseite](https://komfortkasse.eu/preise/). Neue Accounts können Komfortkasse kostenlos testen.

### Ist das Komfortkasse-Plugin eine Zahlungsart?

Nein. Das Komfortkasse-Plugin fügt im Checkout keine Zahlungsart hinzu. Es arbeitet mit den Zahlungsarten, die der VirtueMart-Shop bereits nutzt, zum Beispiel einer Zahlungsart für Vorkasse per Überweisung, für Rechnung oder für Nachnahme. Welche Zahlungsarten das Plugin berücksichtigt, wählen Sie in den Einstellungen je Zahlungsart aus der Liste der VirtueMart-Zahlungsarten. Die Zahlungsart „Komfortkasse“, die Sie bei der Installation anlegen, enthält nur die Einstellungen des Plugins und bleibt unveröffentlicht.

### Was passiert, wenn die Bestellnummer fehlt oder der Betrag abweicht?

Komfortkasse ordnet Zahlungen fehlertolerant zu. Die Zuordnung funktioniert auch, wenn die Bestellnummer im Verwendungszweck fehlt, der Name des Zahlers nicht zum Besteller passt oder der Betrag abweicht. Auch Teil- und Überzahlungen behandelt Komfortkasse.

### Was passiert, wenn ein Käufer nicht zahlt?

Komfortkasse sendet automatisch Zahlungserinnerungen an den Käufer. Bleibt die Zahlung aus, setzt das Plugin die Bestellung in VirtueMart auf den Status, den Sie dafür gewählt haben. Die Einstellungen unterscheiden nach Zahlungsart: „Vorkasse: Status storniert“ (ab Werk `X`, Cancelled), „Rechnung: Status keine Zahlung/Inkasso“ (ab Werk `D`, Denied) und „Nachnahme: Status Zahlung ungeklaert“ (ab Werk `X`, Cancelled). Geht das Geld danach doch noch ein, ändert das Plugin die Bestellung nicht mehr, solange der gewählte Status nicht zu den offenen Status gehört.

### Welche Banken unterstützt Komfortkasse?

Komfortkasse arbeitet mit Ihrem eigenen Bankkonto oder mit einem integrierten Bankkonto von Komfortkasse. Welche Banken sich anbinden lassen, zeigt die Liste der [unterstützten Banken](https://komfortkasse.eu/banken/).

### Warum legt man für das Komfortkasse-Plugin in VirtueMart eine Zahlungsart an?

Weil VirtueMart die Einstellungen eines Zahlungsplugins in einer Zahlungsart speichert. Das Komfortkasse-Plugin ist technisch ein solches Plugin (Joomla-Plugin der Gruppe `vmpayment`) und liest seine Einstellungen und die Verbindungsdaten aus der Zahlungsart „Komfortkasse“ (Tabelle `#__virtuemart_paymentmethods`). Laut Installationsanleitung legen Sie diese Zahlungsart mit „Published“ = „No“ an. Unveröffentlichte Zahlungsarten zeigt VirtueMart im Checkout nicht an, und das Plugin bringt auch keine eigene Anzeige im Checkout mit; Käufer sehen die Zahlungsart „Komfortkasse“ deshalb nicht.

### Müssen für das Komfortkasse-Plugin Dateien von VirtueMart geändert werden?

Nein. Das Komfortkasse-Plugin wird über die Erweiterungsverwaltung von Joomla installiert und liegt komplett im Ordner `plugins/vmpayment/komfortkasse`. Für die Echtzeit-Übertragung nutzt es das Ereignis `plgVmConfirmedOrder`, das VirtueMart beim Bestellabschluss auslöst; die Schnittstelle zu Komfortkasse läuft über den Aufruf `index.php?option=com_virtuemart&view=cart&type=vmpayment&name=komfortkasse`. Code-Ergänzungen in VirtueMart oder Joomla sind nicht nötig.

### Gibt es Komfortkasse auch für andere Shopsysteme?

Ja. Komfortkasse gibt es unter anderem für Shopify, Shopware, WooCommerce, PrestaShop, Gambio und xt:Commerce. Alle Systeme stehen in der [Übersicht der Shopsysteme](https://komfortkasse.eu/shopsysteme/), weitere Plugin-Repositories unter [github.com/ltckomfortkasse](https://github.com/ltckomfortkasse).

## Support

Fragen zur Einrichtung beantworten die [Hilfe-Seiten](https://komfortkasse.freshdesk.com/support/solutions/14000075022) und der [Komfortkasse-Support](https://komfortkasse.eu/kontakt/).

## English

**Komfortkasse plugin for VirtueMart (the shop component for Joomla!).** Komfortkasse automatically matches incoming bank transfers to open orders in your shop and updates the order status as soon as the payment has arrived. It supports prepayment by bank transfer (Vorkasse), invoice and cash on delivery. Komfortkasse is a service of LTC Information Services GmbH for online merchants in Germany, Austria and Switzerland.

The plugin supports VirtueMart 3.0 or later. It does not add a payment method to the checkout; it works with the shop's existing payment methods. The plugin is free of charge; Komfortkasse itself is a paid service ([pricing](https://komfortkasse.eu/preise/)). Technically it is a VirtueMart payment plugin (Joomla plugin group `vmpayment`): its settings are stored in a payment method named "Komfortkasse" that stays unpublished, so buyers never see it. When a payment arrives or an order is cancelled, the plugin sets the VirtueMart order status you chose and adds an entry to the order history.

Installation: download the plugin ZIP from the landing page, install it in the Joomla administrator via Extension Manager → Upload & Install, create an unpublished payment method "Komfortkasse" under VirtueMart → Payment Methods, choose the settings in its Configuration tab (always include "Pending" in the open states for prepayment) and connect the shop in your Komfortkasse account. More information: [Komfortkasse for VirtueMart](https://komfortkasse.eu/shopsysteme/virtuemart/) · [Installation guide (English)](https://komfortkasse.freshdesk.com/support/solutions/articles/14000161605)
