class AddAttachmentUploadToNareshes < ActiveRecord::Migration
  def self.up
    change_table :nareshes do |t|
      t.attachment :upload
    end
  end

  def self.down
    drop_attached_file :nareshes, :upload
  end
end
