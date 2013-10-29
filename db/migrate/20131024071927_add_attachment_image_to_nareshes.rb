class AddAttachmentImageToNareshes < ActiveRecord::Migration
  def self.up
    change_table :nareshes do |t|
      t.attachment :image
    end
  end

  def self.down
    drop_attached_file :nareshes, :image
  end
end
